<template>
  <main>
    <div class="container rounded bg-white">
      <div class="row d-flex justify-content-center pb-5">
        <div class="col-sm-5 col-md-5 ml-1">
          <div class="py-4 d-flex flex-row">
            <h3>Checkout</h3>
          </div>
    
          <h4>Delivery Address</h4>
          <div class="d-flex pt-2">
            <div>
              <p>Where you would like your order delivered?</p>
            </div>
            <div class="ml-auto">
              <p class="text-primary">Add new address</p>
            </div>
          </div>
          <div class="delivery-addresses">
            <label id="delivery-address">
              <input type="radio" name="variation" />
              <div class="address-details">
                <p class="address-name">Main Kitchen</p>
                <p class="address-location">Trademark, Limuru Road</p>
              </div>
            </label>

            <label id="delivery-address">
              <input type="radio" name="variation" />
              <div class="address-details">
                <p class="address-name">Main Kitchen</p>
                <p class="address-location">Trademark, Limuru Road</p>
              </div>
            </label>
          </div>
          <hr />

          <h4>Delivery Date</h4>
          <div class="d-flex pt-2">
            <div>
              <p>When you would like to receive your order?</p>
            </div>
          </div>

          <div>
            <v2-datepicker lang="en"></v2-datepicker>
          </div>
          <hr />

          <h4>Payment</h4>
          <div class="d-flex pt-2">
            <div>
              <p>How you would like to pay for your order?</p>
            </div>
          </div>
          <hr />
        </div>
        <div class="col-sm-3 col-md-4 offset-md-1 mobile">
          <div class="p-4 bg-light rounded d-flex flex-column">
            <div class="p-2 ml-3">
              <h4>Order Recap</h4>
            </div>
            <div class="p-2 d-flex">
              <div class="col-8">Contracted Price</div>
              <div class="ml-auto">$186.76</div>
            </div>
            <div class="p-2 d-flex">
              <div class="col-8">Amount toward deductible</div>
              <div class="ml-auto">$0.00</div>
            </div>
            <div class="p-2 d-flex">
              <div class="col-8">Coinsurance( 0% )</div>
              <div class="ml-auto">+ $0.00</div>
            </div>
            <div class="p-2 d-flex">
              <div class="col-8">Copayment</div>
              <div class="ml-auto">+ $40.00</div>
            </div>
            <div class="border-top px-4 mx-3"></div>
            <div class="p-2 d-flex pt-3">
              <div class="col-8">Total Deductible, Coinsurance, and Copay</div>
              <div class="ml-auto">$40.00</div>
            </div>
            <div class="p-2 d-flex">
              <div class="col-8">
                Maximum out-of-pocket on Insurance Policy (not reached)
              </div>
              <div class="ml-auto">$6500.00</div>
            </div>
            <div class="border-top px-4 mx-3"></div>
            <div class="p-2 d-flex pt-3">
              <div class="col-8">Insurance Responsibility</div>
              <div class="ml-auto"><b>$71.76</b></div>
            </div>
            <div class="p-2 d-flex">
              <div class="col-8">
                Patient Balance
                <span class="fa fa-question-circle text-secondary"></span>
              </div>
              <div class="ml-auto"><b>$71.76</b></div>
            </div>
            <div class="border-top px-4 mx-3"></div>
            <div class="p-2 d-flex pt-3">
              <div class="col-8"><b>Total</b></div>
              <div class="ml-auto"><b class="green">$85.00</b></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style lang="scss" scoped>
main {
  background-color: #eeeeee;
  padding: 1.5rem;
}

.delivery-addresses {
  display: flex;
  flex-flow: row wrap;
  justify-content: space-evenly;
  align-items: center;
}

.delivery-addresses input {
  display: none;
}

.address-details {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border: 1px solid #a7aca3;
  border-radius: 4px;
  box-sizing: border-box;
  transition: border-color 0.12s ease-out 0s;
  cursor: pointer;
  padding: 12px 10px;

  p {
      margin: 0;
  }

  .address-name {
      font-weight: bold;
      margin-bottom: 8px;
  }
}

.address-details:hover {
  border-color: #77d633;
  outline: none;
}

.delivery-addresses input:checked + .address-details {
  border-color: #77d633;
  background-color: rgba(119, 214, 51, 0.07);
  box-shadow: 0 0 0 1px #77d633 inset;
  cursor: default;
}
</style>