<template>
  <div>
    <div v-if="productCategories.length && !showSearch" class="container content-container">
      <template v-for="category in productCategories">
        <template v-if="category.products.length">
          <h3 class="category-title" :key="category.name">
            <span>{{ category.name }}</span>
          </h3>
          <div class="row" :key="category.id">
            <div
              class="col-md-2 products-grid-item"
              @click="showProductInfo(product)"
              v-for="product in category.products"
              :key="product.product_id"
              v-b-modal.product-popup
            >
              <img
                class="product-image"
                :src="product.thumb"
                :alt="product.name"
              />
              <p class="product-name">{{ product.name }}</p>
              <p class="product-price">
                {{ product.variations[0].special_price }} / Per
                {{ product.variations[0].unit }}
              </p>
            </div>
          </div>
          <div class="row mb-5" :key="category.href">
            <div
              class="col-md-12 d-flex justify-content-center align-items-center"
            >
              <a href="#" class="btn btn-sm btn-success px-4 py-2"
                >View All {{ category.name }}
              </a>
            </div>
          </div>
        </template>
      </template>
    </div>

    <div class="container" v-if="showSearch">
      <h3 class="category-title">
        <span>Searching {{ searchQuery }}</span>
      </h3>
      <div class="row" v-if="searchResults.length">
        <template v-for="product in searchResults">
          <div
            class="col-md-2 products-grid-item"
            :key="product.product_id"
            @click="showProductInfo(product)"
            v-b-modal.product-popup
          >
            <img
              class="product-image"
              :src="product.thumb"
              :alt="product.name"
            />
            <p class="product-name">{{ product.name }}</p>
            <p class="product-price">
              {{ product.variations[0].special_price }} / Per
              {{ product.variations[0].unit }}
            </p>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import NProgress from "nprogress";

export default {
  computed: {
    ...mapGetters("products", ["searchResults"]),
    ...mapState("products", ["isSearchActive", "searchQuery", "productCategories"]),

    showSearch() {
      return this.searchQuery.length != 0 && this.searchQuery.trim() != "";
    }
  },

  methods: {
    showProductInfo(product) {
      this.$store.state.cart.selectedProduct = product;
    },
  },

  created() {
    this.$store.dispatch("products/getCategoriesWithProducts");
  },

  watch: {
    isSearchActive(isActive) {
      if(isActive) {
        NProgress.start();
      } else {
        NProgress.done();
      }
    }
  }
};
</script>