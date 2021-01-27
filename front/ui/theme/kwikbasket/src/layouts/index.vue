<template>
  <div class="super_container">
    <Header />
    <transition name="page" mode="out-in">
      <router-view />
    </transition>
  </div>
</template>

<script>
import Header from "../components/Header";

export default {
  components: {
    Header,
  },
};
</script>