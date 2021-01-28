<template>
  <b-modal id="product-popup" @hide="clearSelectedProduct()">
    <template #modal-header="{ close }">
      <h4 class="modal-title">
        {{ selectedProduct.name }}
      </h4>
      <button type="button" class="close" @click="close">
        <span aria-hidden="true">&times;</span>
      </button>
    </template>

    <template #default>
      <div class="container">
        <div class="row">
          <div
            class="col-md-12 d-flex justify-content-center align-items-center"
          >
            <img
              class="img-fluid product-thumbnail"
              :src="selectedProduct.thumb"
            />
          </div>
        </div>
        <h6 class="modal-section-title">Available In</h6>
        <div class="row modal-row">
          <div class="col-md-12 variations-container">
            <label
              id="variation-selector"
              v-for="variation in selectedProduct.variations"
              :key="variation.variation_id"
            >
              <input
                type="radio"
                name="variation"
                :value="variation"
                v-model="popup.variation"
              />
              <span class="variation-pill">{{ variation.unit }}</span>
            </label>
          </div>
        </div>
        <h6 class="modal-section-title">Product Notes (Optional)</h6>
        <div class="row modal-row">
          <div class="col-md-12 px-0">
            <textarea
              class="form-control"
              id="product-notes"
              placeholder="Tell us how you'd like this product e.g Big, Ripe, Peeled, etc."
              v-model="popup.productNotes"
              rows="3"
            ></textarea>
          </div>
        </div>
      </div>

      <div v-if="popup.variation.price" class="price-container">
        <p class="product-modal-price">
          {{ formatCurrency(popup.variation.price) }}
        </p>
      </div>
    </template>

    <template #modal-footer>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="input-group">
              <input
                id="product-quantity"
                class="form-control"
                placeholder="Quantity"
                v-model="popup.quantity"
              />
              <input
                type="button"
                class="btn btn-cta-add"
                value="Add To Basket"
                :disabled="!popup.isValidData"
                @click="addProductToCart"
              />
            </div>
          </div>
        </div>
      </div>
    </template>
  </b-modal>
</template>

<script>
import { mapState } from "vuex";
import currency from "../util/currency";

export default {
  data() {
    return {
      popup: this.getDefaultState(),
    };
  },

  computed: {
    ...mapState("cart", ["selectedProduct"]),
  },

  methods: {
    formatCurrency(amount) {
      return currency(amount, { symbol: "KES " }).format();
    },

    getDefaultState() {
      return {
        variation: {},
        quantity: "",
        productNotes: "",
        produceType: "",
        isValidData: false,
      };
    },

    addProductToCart() {
      this.$store.dispatch("cart/addProductToCart", {
        ...this.selectedProduct,
        variation_id: this.popup.variation.variation_id,
        key: this.popup.variation.key,
        price: this.popup.variation.price,
        quantity: this.popup.quantity,
        productNotes: this.popup.productNotes,
        produceType: this.popup.produceType,
      });
      this.$root.$emit('bv::hide::modal', 'product-popup')
    },

    clearSelectedProduct() {
      this.popup = this.getDefaultState();
    },
  },

  watch: {
    popup: {
      handler(popup) {
        const { variation, quantity } = popup;
        this.popup.quantity = quantity.replace(/[^0-9\.]/g, "");

        if (quantity > 0 && Object.keys(variation).length) {
          this.popup.isValidData = true;
        } else {
          this.popup.isValidData = false;
        }
      },
      deep: true,
    },
  },
};
</script>