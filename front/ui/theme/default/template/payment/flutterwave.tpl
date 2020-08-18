<h2><?php echo $text_instruction; ?> - Choose Your Preferred Payment Option</h2>
<br>
<span class="input-group-btn" style="padding-bottom: 10px;">
    <div class="form-group">
        <div class="col-sm-6 col-md-4">
            <select id="payment_options" name="payment_options" class="form-control">
                <option value="">Select Payment Option</option>
                <option value="account">Account</option>
                <option value="card">Card</option>
                <option value="banktransfer">Bank Transfer</option>
                <option value="mpesa">mPesa</option>
                <option value="mobilemoneyrwanda">Mobile Money Rwanda</option>
                <option value="mobilemoneyzambia">Mobile Money Zambia</option>
                <option value="qr">QR</option>
                <option value="mobilemoneyuganda">Mobile Money Uganda</option>
                <option value="ussd">USSD</option>
                <option value="credit">Credit</option>
                <option value="barter">Barter</option>
                <option value="mobilemoneyghana">Mobile Money Ghana</option>
                <option value="payattitude">PayAttitude</option>
                <option value="mobilemoneyfranco">Mobile Money Franco</option>
                <option value="paga">Paga</option>
                <option value="1voucher">1Voucher</option>
                <option value="mobilemoneytanzania">Mobile Money Tanzania</option>
            </select> 
        </div>
    </div>   
</span>
<button type="button" id="button-confirm" class="btn btn-default">PAY &amp; CONFIRM</button>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
    $('#button-confirm').on('click', function () {
        var payment_options = $('#payment_options').val();
        if (payment_options != null && payment_options.length > 0) {
        } else {
            alert('Please select Payment options!');
            return false;
        }
    });

    $(document).delegate('#button-confirm', 'click', function () {
        console.log("button-confirm click");
        var payment_options = $('#payment_options').val();
        $.ajax({
            url: 'index.php?path=payment/flutterwave/confirm',
            type: 'post',
            data: { 'payment_option': payment_options },
            dataType: 'json',
            success: function (json) {
                console.log(json);
            if(json.status == 'success') {
                window.location.href = json.data.link;
            } else { 
               alert('Something went wrong please try again later!'); 
            } 
            }
        });
    });
</script>