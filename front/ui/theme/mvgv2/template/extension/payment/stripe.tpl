<form action="." method="post" id="payment-form">
        <noscript>You must <a href="http://www.enable-javascript.com" target="_blank">enable JavaScript</a> in your web browser in order to pay via Stripe.</noscript>

        <input 
            type="submit" 
            value="Pay with Card"
            
            data-key='<?php echo $publishable_key; ?>'
            data-amount="<?= $amount ?>"
            data-currency="usd"
            data-email="<?php echo $customer_email; ?>"
            data-name="FreshBazaar"
            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"

            data-description="Stripe payment for $<?= $amount_in_decimal ?>"
            id="stripe-button-confirm" 
            data-toggle="collapse"
            data-loading-text="<?= $text_loading ?>" 
            class="btn btn-default"
        />

        <script src="https://checkout.stripe.com/v2/checkout.js"></script>

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
        

        <script>
        $(document).ready(function() {
            $(':submit').on('click', function(event) {

              $('#stripe-button-confirm').button('loading');

                event.preventDefault();

                var $button = $(this);
                var $button_confirm = $('#stripe-button-confirm');
                $button_confirm.attr('disabled', true);

                $form = $button.parents('form');

                var opts = $.extend({}, $button.data(), {
                    token: function(result) {
                        //$form.append($('<input>').attr({ type: 'hidden', name: 'stripeToken', value: result.id })).submit();
                        stripeResponseHandler(result);
                    }
                });

                StripeCheckout.open(opts);
            });

            function stripeResponseHandler(response) {

              // Grab the form:
              var $form = $('#payment-form');

              var $button_confirm = $('#stripe-button-confirm');

               $('#stripe-button-confirm').button('loading');

              if (response.error) { // Problem!
                $('.alert').remove();
                $button_confirm.attr('disabled', false);

                // Show the errors on the form
                $form.before('<div class="alert alert-danger text-center">'+ response.error.message +'</div>');
                //alert('error');
                $button_confirm.prop('disabled', false);
                // $('#stripe-button-confirm').button('reset');

              } else { // Token was created!

                // Get the token ID:
                var token = response.id;
                $.ajax({
                  url: 'index.php?path=payment/stripe/send',
                  type: 'post',
                  data: { 
                    card: token,
                    saveCreditCard: !!$('#save-credit-card').prop('checked'),
                    existingCard: false
                  },
                  dataType: 'json',
                  complete: function() {
                    $('.alert').remove();
                    //$button_confirm.prop('disabled', false);
                    $('#stripe-button-confirm').button('reset');
                  },
                  error: function(json) {
                    //alert(json);
                    $form.before('<div class="alert alert-danger text-center">'+ response.error.message +'</div>');
                  },
                  success: function(json) {
                    if (json['error']) {
                      //alert(json['error']);
                      $form.before('<div class="alert alert-danger text-center">'+ response.error.message +'</div>');
                    }
                  
                    if (json['success']) {
                      location = json['success'];
                    }
                  }
                });

              }
            }
        });
        </script>
</form>