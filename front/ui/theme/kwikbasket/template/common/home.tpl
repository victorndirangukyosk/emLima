<?php require(DIR_BASE.'front/ui/theme/kwikbasket/template/common/header.tpl'); ?>

<div v-if="productCategories.length && !isSearchActive" class="container content-container">
  <template v-for="category in productCategories">
    <template v-if="category.products.length">
      <h3 class="category-title" :key="category.id">
        <span>{{ category.name }}</span>
      </h3>
      <div class="row">
        <template v-for="product in category.products">
          <div class="col-md-2 products-grid-item" :key="product.product_id" @click="showProductPopup(product)">
            <img class="product-image" :src="product.thumb" :alt="product.name">
            <p class="product-name">{{ product.name }}</p>
            <p class="product-price">{{ product.variations[0].special_price}} / Per {{ product.variations[0].unit}}</p>
          </div>
        </template>
      </div>
      <div class="row mb-5">
        <div class="col-md-12 d-flex justify-content-center align-items-center">
          <a href="#" class="btn btn-sm btn-success px-4 py-2">View All {{ category.name }}
          </a>
        </div>
      </div>
    </template>
  </template>
</div>

<div class="container" v-else-if="isSearchActive">
  <h3 class="category-title">
    <span>Searching {{ searchQuery }}</span>
  </h3>
  <div class="row" v-if="searchResults.length">
    <template v-for="product in searchResults">
      <div class="col-md-2 products-grid-item" :key="product.product_id" @click="showProductPopup(product)">
        <img class="product-image" :src="product.thumb" :alt="product.name">
        <p class="product-name">{{ product.name }}</p>
        <p class="product-price">{{ product.variations[0].special_price}} / Per {{ product.variations[0].unit}}</p>
      </div>
    </template>
  </div>
  <div v-else class="d-flex justify-content-center align-items-center" style="height: 45vh">
    <img src="<?= $base ?>front/ui/theme/kwikbasket/assets/images/spinner.gif" alt="Loading spiner">
  </div>
</div>

<div v-else class="d-flex justify-content-center align-items-center" style="height: 45vh">
  <img src="<?= $base ?>front/ui/theme/kwikbasket/assets/images/spinner.gif" alt="Loading spiner">
</div>

<?php require(DIR_BASE.'front/ui/theme/kwikbasket/template/common/footer.tpl'); ?>