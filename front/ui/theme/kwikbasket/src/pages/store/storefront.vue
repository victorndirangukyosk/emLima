<template>
  <div
    v-if="productCategories.length"
    class="container content-container"
  >
    <template v-for="category in productCategories">
      <template v-if="category.products.length">
        <h3 class="category-title" :key="category.name">
          <span>{{ category.name }}</span>
        </h3>
        <div class="row" :key="category.id">
            <div
              class="col-md-2 products-grid-item"
              v-for="product in category.products" :key="product.product_id"
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
        <div class="col-md-12 d-flex justify-content-center align-items-center">
          <a href="#" class="btn btn-sm btn-success px-4 py-2">View All {{ category.name }}
          </a>
        </div>
      </div>
      </template>
    </template>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

export default {
  computed: mapState({
    productCategories: (state) => state.products.productCategories,
  }),

  created() {
    this.$store.dispatch("products/getCategoriesWithProducts");
  },
};
</script>