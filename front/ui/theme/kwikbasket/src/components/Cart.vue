<template>
  <b-modal id="mini-cart-panel" hide-footer="!itemsInBasket">
    <template #modal-header="{ close }">
      <h5 class="modal-title">Basket</h5>
      <button type="button" class="close" @click="close">
        <span aria-hidden="true">&times;</span>
      </button>
    </template>

    <template #default>
      <div id="mini-cart" class="container">
        <template v-if="cartProducts.length">
          <div v-for="product in cartProducts" :key="product.key">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="modal-section-title">{{ product.name }}</h6>
                <h5 class="mt-2 text-success">
                  <strong>{{ productTotalPrice(product) }}</strong>
                </h5>
              </div>
              <div
                class="cart-remove-button"
                @click="removeProductFromCart(product)"
              >
                <img :src="removeIcon" alt="Remove from cart" />
              </div>
            </div>

            <div class="row modal-row">
              <div class="col-md-12">
                <div class="d-flex flex-wrap justify-content-between">
                  <img
                    class="cart-product-thumbnail"
                    :src="product.thumb"
                    :alt="product.name"
                  />
                  <div
                    class="cart-quantity-controls d-flex flex-column align-items-center justify-content-around"
                  >
                    <span class="mb-2">{{ product.price }}</span>
                    <div class="d-flex flex-row justify-content-center">
                      <button
                        type="button"
                        class="btn btn-success btn-minus btn-qty"
                        @click="decrementProductQuantity(product)"
                      >
                        -
                      </button>
                      <input
                        type="text"
                        disabled="disabled"
                        class="cart-product-quantity"
                        :value="product.quantity"
                      />
                      <button
                        type="button"
                        class="btn btn-success btn-plus btn-qty"
                        @click="incrementProductQuantity(product)"
                      >
                        +
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <h6 v-else class="text-muted text-center mt-5">
          No products in your basket
        </h6>
      </div>
    </template>

    <template #modal-footer>
      <a href="#" class="btn btn-success btn-cta"
        >Checkout ({{ amountInCart }})</a
      >
    </template>
  </b-modal>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import currency from "../util/currency";

export default {
  data: () => ({
    removeIcon: require("@/assets/images/icons/remove.svg"),
  }),

  computed: {
    ...mapGetters({
      itemsInBasket: "cart/itemsInBasket",
      basketCost: "cart/basketCost",
    }),

    ...mapState("cart", ["cartProducts"]),

    amountInCart() {
      return this.formatCurrency(this.basketCost);
    },
  },

  methods: {
    incrementProductQuantity(product) {
      const key = product.key;
      const quantity = (parseFloat(product.quantity) + 1).toFixed(1);
      this.$store.dispatch("cart/updateProductQuantity", { key, quantity });
    },

    decrementProductQuantity(product) {
      const key = product.key;
      const quantity = (parseFloat(product.quantity) - 1).toFixed(1);

      if (quantity <= 0) {
        this.$store.dispatch("cart/removeProductFromCart", product);
      } else {
        this.$store.dispatch("cart/updateProductQuantity", { key, quantity });
      }
    },

    removeProductFromCart(product) {
      this.$store.dispatch("cart/removeProductFromCart", product);
    },

    productTotalPrice({ price, quantity }) {
      const total = currency(price).value * quantity;
      return this.formatCurrency(total);
    },

    formatCurrency(amount) {
      return currency(amount, { symbol: "KES " }).format();
    },
  },

  created() {
    this.$store.dispatch("cart/getProductsInCart");
  },
};
</script>