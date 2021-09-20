<div class="checkout-container" style="display:none;">
    <h4>Checkout - Kwik Basket</h4>

    <form id="submit-form">
        <div class="form-input">
            <label>Merchant Code</label>
            <!-- <input id="param-merchantCode" value="MX8067" required /> -->
            <input id="param-merchantCode" required="" value="<?=$interswitch_merchant_code; ?>" />
        </div>

        <div class="form-input">
            <label>Item Id</label>
            <!-- <input id="param-itemId" value="3634738" required /> -->
            <input id="param-itemId" required="" value="<?=$interswitch_pay_item_id; ?>" />
        </div>

        <div class="form-input">
            <label>Transaction Reference</label>
            <input id="param-transRef" required="" value="<?=$interswitch_data_ref; ?>" />
        </div>

        <div class="form-input">
            <label>Amount</label>
            <input id="param-amount" required="" value="<?=$interswitch_amount; ?>" />
        </div>

        <div class="form-input">
            <label>Customer Name(Optional)</label>
            <input id="param-customerName" value="<?=$interswitch_customer_name; ?>" />
        </div>

        <div class="form-input">
            <label>Customer Id(Optional)</label>
            <input id="param-customerId" value="<?=$interswitch_customer_id; ?>" />
        </div>

        <div class="form-input">
            <label>Mode(TEST or LIVE)</label>
            <input id="param-mode" required="" value="TEST" />
            <input id="param-url" required="" value="<?=$this->url->link('payment/interswitch/status', '', 'SSL'); ?>"
        </div>

        <button type="submit">Pay</button>
    </form>
</div>

<script type="text/javascript" src="https://qa.interswitchng.com/collections/public/javascripts/inline-checkout.js"></script>
<script type="text/javascript">
var submitForm = document.getElementById("submit-form");

submitForm.addEventListener("submit", submitHandler);

function submitHandler(event) {
  event.preventDefault();

  var merchantCode = document.getElementById("param-merchantCode").value;
  var itemId = document.getElementById("param-itemId").value;
  var transRef = document.getElementById("param-transRef").value;
  var amount = document.getElementById("param-amount").value;

  var customerName = document.getElementById("param-customerName").value;
  var customerId = document.getElementById("param-customerId").value;

  var mode = document.getElementById("param-mode").value;

  var redirectUrl = document.getElementById("param-url").value;

  var paymentRequest = {
    merchant_code: merchantCode,
    pay_item_id: itemId,
    txn_ref: transRef,
    amount: amount,
    currency: 404,
    site_redirect_url: redirectUrl,
    onComplete: paymentCallback,
    mode: mode
  };

  if (customerName != "") {
    paymentRequest.cust_name = customerName;
  }

  if (customerId != "") {
    paymentRequest.cust_id = customerId;
  }

  window.webpayCheckout(paymentRequest);
}

function paymentCallback(response) {
console.log(response);
    $.ajax({
        url: 'index.php?path=payment/interswitch/InterswitchPaymentResponse',
        type: 'post',
        data: {
            payment_response : response
        },
        dataType: 'json',
        cache: false,
        async: true,
        beforeSend: function() {
        },
        success: function(json) {
            console.log(json.redirect_url);
            if(json.redirect_url != null) {
            $(window).attr('location',json.redirect_url);
        }
            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        },
        complete: function() {            
        },
    });  
}    
</script>