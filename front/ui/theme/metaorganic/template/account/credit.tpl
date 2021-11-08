<?php echo $header; ?>
                        <div class="col-md-9 nopl">
                            <div class="dashboard-cash-content">
                                 
                                <div class="row">
                                     <div class="col-md-12">
                                            <div class="cash-info" style="padding-bottom: 50px;padding-top: 50px;"><h1><?= $text_balance ?></h1>
                                            <div class="cash-block">
                                              <span class="your-cash"> <?= $total ?>  </span>
                                            </div>
                                            <!--<a href="<?= $home ?>" class="btn btn-primary"><?= $text_shopping?></a>-->
                                            </div>
                                     </div>


                                      <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                                    <div class="col-md-9" id="pay_with" >
                                    Top Up With
                                    <div class="row">
                                        <!--<div class="col-md-4">
                                            <div class="radio">
                                                <label><input class="option_pay" onchange="payOptionSelected()" type="radio" name="pay_with">PesaPal</label>
                                            </div>
                                        </div>-->
                                        <div class="col-md-6">
                                            <div class="radio">
                                                <label><input class="option_pay" onchange="payWithmPesa()" type="radio" name="pay_with">mPesa Online</label>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-6">
                                            <div class="radio">
                                                <label><input class="option_pay" onchange="LoadInterSwitch()" type="radio" name="pay_with">Interswitch</label>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>

                                 <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id'];?>">
                                 
                                 

                                   <div id="pay-amount" class="col-md-12 confirm_order_class" style="display:none; ">
                                    <p>Amount</p>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <span class="input-group-btn" style="padding-bottom: 5px;">
                                                

                                                <input id="amount_topup" name="amount_topup" type="text" value="" class="form-control input-md" required="" placeholder="Amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" style="display: inline-block;    width: 50%;" >

                                            </span>
                                        </div>   
                                    </div>
                                    
                                </div>
                                
 
             <input type="hidden" name="mpesa_checkout_request_id" id="mpesa_checkout_request_id" value="">

                                <div id="pay-confirm-order-mpesa" class="col-md-12 confirm_order_class" style="display:none; ">
                                    <p>mPesa Online</p>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span class="input-group-btn" style="padding-bottom: 10px;">
                                                <p id="button-reward" class="" style="padding: 13px 14px;    margin-top: -9px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">
                                                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">+254</font></font></font></font>
                                                </p>

                                                <input id="mpesa_phone_number" name="telephone" type="text" value="<?php echo $this->customer->getTelephone(); ?>" class="form-control input-md" required="" placeholder="Mobile number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" style="display: inline-block;    width: 50%;" >

                                            </span>
                                        </div>
                                        <div class="col-md-5">
                                            <button type="button" id="mpesa-button-confirm" data-toggle="collapse" data-loading-text="checking phone..." class="btn btn-default">PAY &amp; CONFIRM</button>
                                            
                                            <button type="button" id="button-retry" class="btn btn-default"> Retry</button>

                                            <button type="button" id="button-complete" data-toggle="collapse" data-loading-text="checking payment..." class="btn btn-default">Confirm Payment</button>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px;">
                                            </div>
                                            <div class="alert alert-success" style="font-size: 14px;" id="success_msg" style="margin-bottom: 7px;">
                                            </div>
                                        </div>    
                                    </div>
                                    <!--MPESA REMOVED FROM HERE-->
                                </div>
                                
                                </div>
                                
                                </div>
                            


                                <?php if ($credits) { ?>
                                 <div class="credit-details">
                                    <?php foreach ($credits  as $credit) { ?>
                                    <div class="my-order"><!-- 25 Dec 2015 -->
                                      <div class="list-group my-order-group">
                                          <li class="list-group-item my-order-list-head"><i class="fa fa-clock-o"></i> <?= $text_activity?> <span><strong><?php echo $credit['date_added']; ?></strong></span><span>

                                          <!-- <a href="#" data-toggle="modal" data-target="#contactusModal" class="btn btn-default btn-xs"><?= $text_report_issue ?> </a> -->

                                          </span></li>
                                          <li class="list-group-item">
                                              <div class="my-order-block">
                                                  <div class="row">
                                                      <div class="col-md-10">
                                                          <div class="my-order-delivery">
                                                              <!-- <?php if($credit['amount'] >= 0) { ?>
                                                                <h3 class="my-order-title">Credit</h3>
                                                              <?php } else { ?>
                                                                    <h3 class="my-order-title">Debit</h3>
                                                              <?php } ?> -->
                                                              <span class="my-order-date"><?php echo $credit['description']; ?></span>
                                                          </div>
                                                      </div>
                                                     <?php if($credit['plain_amount'] >= 0) { ?>
                                                                <div class="col-md-2" style="color: green"><?php echo $credit['amount']; ?></div>
                                                              <?php } else { ?>
                                                                    <div class="col-md-2" style="color: red"><?php echo $credit['amount']; ?></div>
                                                              <?php } ?>
                                                      
                                                  </div>
                                              </div>
                                          </li>
                                      </div>
                                  </div>
                                    <?php } ?>
                                    <?php } else { ?>
                                      <center class="text-center" colspan="5"><?php echo $text_empty; ?></center>
                                <?php } ?>
                                 </div>
                                    

                                <div class="text-right" style='display: none;'>
                                    <?php echo $pagination; ?>
                                </div>

                                <?php if(!empty($pagination)) { ?>
                                    <div id="button-area">
                                        <button class="load_more btn btn-default center-block" type="button">
                                            <span class="load-more-text"><?= $text_load_more?></span>
                                            <div class="load-more-loader" style="display: none;"></div>
                                        </button>    
                                    </div>
                                <?php } ?>

                                <!-- <div class="row">
                                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                                </div>
                                  <br />
                                  <?php echo $content_bottom; ?> -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php echo $footer; ?>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/manual-trigger.js" ></script>

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


    var page_category = 'credit-page';
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

        $(document).ready(function() {
            var $container = $('.credit-details');
            $container.infinitescroll({
                animate:true,
                navSelector  : '.pagination',    // selector for the paged navigation 
                nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
                itemSelector : '.credit-details',
                loading: {
                    finishedMsg: '<?php echo $text_no_more ?>',
                    msgText: 'Loading...',
                    img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'
                    
                },
                errorCallback: function () { 
                    $('.load-more-text').html('<?= $text_load_more?>');
                    $('.load-more-loader').hide(); 
                }
            }, function(json, opts) {
                $('.load-more-text').html('<?= $text_load_more?>');
                $('.load-more-loader').hide();
            });

            $(window).unbind('.infscr');

            $(document).on('click', '.load_more', function () {
                var text = $('.load-more-text').html();
                $('.load-more-text').html('');
                $('.load-more-loader').show();
                $container.infinitescroll('retrieve');
                return false;
            });

            /**/
    });


     function payWithmPesa() {
        $("#pay-confirm-order").html('');
        $("#pay-confirm-order").hide();
        $("#pay-confirm-order-mpesa").show();
        $("#pay-amount").show();
    }

    </script>


    <script type="text/javascript">

        $('#error_msg').hide();
        $('#success_msg').hide();
        $('#button-complete').hide();
        $('#button-retry').hide();
	
        $( document ).ready(function() {
            console.log("referfxx def");
            if($('#mpesa_phone_number').val().length >= 9) {
                $( "#mpesa-button-confirm" ).prop( "disabled", false );
            } else {
                $( "#mpesa-button-confirm" ).prop( "disabled", true );
            }
        });

        $('#mpesa_phone_number').on('input', function() { 
            console.log("referfxx");
            if($(this).val().length >= 9) {
                $( "#mpesa-button-confirm" ).prop( "disabled", false );
            } else {
                $( "#mpesa-button-confirm" ).prop( "disabled", true );
            }
        });

        $('#mpesa-button-confirm,#button-retry').on('click', function() {
	    
            $('#loading').show();

            $('#error_msg').hide();
            
            //var radioValue = $("input[name='pay_option']:checked").val();
            var total_amount = $("input[name='amount_topup']").val();
            console.log(total_amount);
            
            if (total_amount <= 0) {
                
            alert('Please enter the amount, you like to top up with');
            return;
            
            }  
            var radioValue ='topup';
            var val = null;
            var total = $("input[name='amount_topup']").val();

            if($('#mpesa_phone_number').val().length >= 9) {
                $.ajax({
                        type: 'post',
                        url: 'index.php?path=payment/mpesa/confirmtransaction',
                        data: { 
                        mobile : encodeURIComponent($('#mpesa_phone_number').val()),
                        order_id: null,
                        amount: total,
                        payment_type: radioValue,
                        payment_method : 'mpesa'
                        },
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                            $(".overlayed").show();
                            $('#mpesa-button-confirm').button('loading');
                        },
                        complete: function() {
                            $(".overlayed").hide();
                        },      
                        success: function(json) {

                                console.log(json);
                                console.log('json mpesa');

                                $('#mpesa-button-confirm').button('reset');
                            $('#loading').hide();

                                if(json['processed']) {
                                        //location = '<?php echo $continue; ?>';
		        		
                                        //$('#success_msg').html('A payment request has been sent to the mpesa number '+$('#mpesa_phone_number').val()+'. Please wait for a few seconds then check for your phone for an MPESA PIN entry prompt.');

                                        $('#success_msg').html('A payment request has been sent on your above number. Please make the payment by entering mpesa PIN and click on Confirm Payment button after receiving sms from mpesa');
		        		$('#mpesa_checkout_request_id').val(json['response'].CheckoutRequestID);
                                        $('#success_msg').show();
		        		
                                        $('#button-complete').show();

                                        console.log('json mpesa1');
                                        $('#mpesa-button-confirm').hide();
                                        $('#button-retry').hide();
                                        console.log('json mpesa2');

                                } else {
                                        console.log('json mpesa err');
                                        console.log(json['error']);
                                        $('#error_msg').html(json['error']);
                                        $('#error_msg').show();
                                }
		            
                        },
                        error: function(json) {

                                console.log('josn mpesa');
                                console.log(json);

                                $('#error_msg').html(json['responseText']);
                                $('#error_msg').show();
                        }
                    });
            }
        });

        $('#button-complete').on('click', function() {
	    
            $('#error_msg').hide();
            $('#success_msg').hide();        
            //var radioValue = $("input[name='pay_option']:checked").val();
            
            var total_amount = $("input[name='amount_topup']").val();
            console.log(total_amount);

            if (total_amount <= 0) {                
            alert('Please enter the amount, you like to top up with');
            return;            
            }  
            var radioValue ='topup';
            var val = null;
            var total = $("input[name='amount_topup']").val();
            

        $.ajax({
                type: 'post',
                url: 'index.php?path=payment/mpesa/completetransaction',
            dataType: 'json',
                cache: false,
                data: { 
                        mobile : encodeURIComponent($('#mpesa_phone_number').val()),
                        order_id: null,
                        amount: total,
                        payment_type: radioValue,
                        payment_method : 'mpesa'
                        },
                beforeSend: function() {
                    $(".overlayed").show();
                    $('#button-complete').button('loading');
                },
                complete: function() {
                    $(".overlayed").hide();
                    $('#button-complete').button('reset');
                },      
                success: function(json) {

                        console.log(json);
                        console.log('json mpesa');
                        if(json['status']) {
                                //success
	        		
                                 $('#success_msg').html('Payment Successfull. Wait Until Page Refresh!');
                                $('#success_msg').show();
                                setInterval(function(){ window.location.replace(json['redirect']); }, 10000);
                        } else {

                                //failed
                                //$('#mpesa-button-confirm').show();
                                //$('#button-retry').hide();
                                //$('#button-complete').hide();

                                $('#error_msg').html(json['error']);
                                $('#error_msg').show();

                                $('#button-complete').hide();
                                $('#button-retry').show();

                        }
	            
                },
                error: function(json) {
                        $('#error_msg').html(json['responseText']);
                        $('#error_msg').show();
                }
            });
        });
</script>

<script type="text/javascript">
$( document ).ready(function() { setInterval(function(){ mpesaresponse(); }, 30000 ); });
function mpesaresponse() {
                if($('#mpesa_checkout_request_id').val() != '') {
                $.ajax({
                        type: 'post',
                        url: 'index.php?path=payment/mpesa/mpesatopupautoupdate',
                        data: { 
                        mpesa_checkout_request_id : encodeURIComponent($('#mpesa_checkout_request_id').val()),
                        },
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        $(".overlayed").show();
                        $('#mpesa-button-confirm').button('loading');
                        },
                        complete: function() {
                        $(".overlayed").hide();
                        },       
                        success: function(json) {
                        if(json['processed'] == true) {
                        $('#mpesa_checkout_request_id').val('');
                        $('#success_msg').html('Payment Successfull. Wait Until Page Refresh!');
                        $('#success_msg').show();
                        setInterval(function(){ window.location.replace(json['redirect']); }, 10000);
                        return false;
                        } 
                        if(json['processed'] == false) {
                        $('#mpesa_checkout_request_id').val('');
                        $('#success_msg').html('');
                        $('#success_msg').hide();
                        $('#error_msg').html(json['mpesa_payments_response'].description);
                        $('#error_msg').show();
                        $('#button-complete').hide();
                        $('#button-retry').show();
                        return false;
                        }
                        if(json['processed'] == '') {
                        $('#mpesa_checkout_request_id').val('');
                        $('#button-complete').show();
                        $('#mpesa-button-confirm').hide();
                        $('#button-retry').hide();
                        $('#loading').hide();
                        return false;
                        }
                        },
                        error: function(json) {
                        console.log(json);
                        }
                });
                }                
}
</script>  
<?php if($redirect_coming) { ?>
<script type="text/javascript">
    $('#save-button').click();
</script>

<?php } ?>

<style>
     

    .option_pay {
        margin-top:-3px !important;
    }
     
</style>


    </body>
</html>