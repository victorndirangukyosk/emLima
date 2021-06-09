<div class="checkout-container">
    <h4>Inline Checkout Demo</h4>

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
        </div>

        <button type="submit">Pay</button>
    </form>
</div>

<script type="text/javascript" src="https://qa.interswitchng.com/collections/public/javascripts/inline-checkout.js"></script>