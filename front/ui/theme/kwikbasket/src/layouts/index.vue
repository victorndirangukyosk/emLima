<template>
  <div class="super_container">
    <Header />
    <Cart />
    <transition name="page" mode="out-in">
      <router-view />
    </transition>
  </div>
</template>

<script>
import Header from "../components/Header";
import Cart from "../components/Cart";

export default {
  components: {
    Cart,
    Header,
  },
};
</script>