<template>
  <div class="super_container">
    <Header />
    <Cart />
    <ProductPopup />
    <transition name="page" mode="out-in">
      <router-view />
    </transition>
  </div>
</template>

<script>
import Header from "../components/Header";
import Cart from "../components/Cart";
import ProductPopup from "../components/ProductPopup";

export default {
  components: {
    Cart,
    Header,
    ProductPopup
  },
};
</script>