<div class="checkout-container">
    <h4>Inline Checkout Demo</h4>

    <form id="submit-form">
        <div class="form-input">
            <label>Merchant Code</label>
            <!-- <input id="param-merchantCode" value="MX8067" required /> -->
            <input id="param-merchantCode" required="" />
        </div>

        <div class="form-input">
            <label>Item Id</label>
            <!-- <input id="param-itemId" value="3634738" required /> -->
            <input id="param-itemId" required="" />
        </div>

        <div class="form-input">
            <label>Transaction Reference</label>
            <input id="param-transRef" required="" />
        </div>

        <div class="form-input">
            <label>Amount</label>
            <input id="param-amount" required="" value="1000" />
        </div>

        <div class="form-input">
            <label>Customer Name(Optional)</label>
            <input id="param-customerName" />
        </div>

        <div class="form-input">
            <label>Customer Id(Optional)</label>
            <input id="param-customerId" />
        </div>

        <div class="form-input">
            <label>Mode(TEST or LIVE)</label>
            <input id="param-mode" required="" value="TEST" />
        </div>

        <button type="submit">Pay</button>
    </form>
</div>

<script type="text/javascript" src="https://qa.interswitchng.com/collections/public/javascripts/inline-checkout.js"></script>