<template>
  <header class="header">
    <div class="top_bar">
      <div class="container">
        <div class="row">
          <div class="col d-flex flex-row">
            <div class="top_bar_contact_item">
              <div class="top_bar_icon">
                <img :src="phoneIcon" alt="Phone Icon" />
              </div>
              +254 738 770 186
            </div>
            <div class="top_bar_contact_item">
              <div class="top_bar_icon">
                <img :src="mailIcon" alt="Mail Icon" />
              </div>
              <a href="mailto:hello@kwikbasket.com">hello@kwikbasket.com</a>
            </div>
            <div class="top_bar_content ml-auto">
              <div class="top_bar_user">
                <div class="user_icon">
                  <img :src="userIcon" alt="User icon" />
                </div>
                <div>Hi, {{ user.firstName }}</div>
                <div><a href="#" @click="logout()">Sign Out</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="header_main">
      <div class="container">
        <div class="row">
          <div class="col-lg-2 col-sm-3 col-3 order-1">
            <div class="logo_container">
              <div class="logo">
                <a href="">
                  <img :src="logo" alt="KwikBasket Logo" />
                </a>
              </div>
            </div>
          </div>

          <div
            class="col-lg-6 col-12 order-lg-2 order-3 text-lg-left text-right"
          >
            <div class="header_search">
              <div class="header_search_content">
                <div class="header_search_form_container">
                  <form action="#" class="header_search_form clearfix">
                    <input
                      id="header-product-search"
                      type="search"
                      class="header_search_input"
                      placeholder="Search for products..."
                      v-model="query"
                    />
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div
            class="col-lg-4 col-9 order-lg-3 order-2 text-lg-left text-right"
          >
            <div
              class="wishlist_cart d-flex flex-row align-items-center justify-content-end"
            >
              <div
                class="wishlist d-flex flex-row align-items-center justify-content-end"
              >
                <div class="wishlist_icon">
                  <img :src="wishlistIcon" alt="Wishlists" />
                </div>
                <div class="wishlist_content">
                  <div class="wishlist_text">
                    <a href="<?= $wishlist ?>">Wishlists</a>
                  </div>
                  <div class="wishlist_count"></div>
                </div>
              </div>

              <div
                class="cart mini-cart-button"
                @click="$bvModal.show('mini-cart-panel')"
              >
                <div
                  class="cart_container d-flex flex-row align-items-center justify-content-end"
                >
                  <div class="cart_icon">
                    <img :src="basketIcon" alt="Basket" />
                    <div class="cart_count">
                      <span class="cart_items_count">
                        {{ itemsInBasket }}
                      </span>
                    </div>
                  </div>
                  <div class="cart_content">
                    <div class="cart_text"><a>Basket</a></div>
                    <div class="cart_price">
                      {{ amountInBasket }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <nav class="main_nav">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="main_nav_content d-flex flex-row">
              <div class="main_nav_menu">
                <ul class="standard_dropdown main_nav_dropdown">
                  <li><a href="<?= $dashboard ?>">Dashboard</a></li>
                  <li>
                    <a href="<?= $po_ocr ?>">
                      Purchase Order
                      <span class="badge badge-pill badge-success">BETA</span>
                    </a>
                  </li>
                  <li class="hassubs">
                    <a href="#"
                      >My Account<i class="fas fa-chevron-down"></i
                    ></a>
                    <ul>
                      <li>
                        <a href="<?= $account ?>"
                          >Profile<i class="fas fa-chevron-down"></i
                        ></a>
                      </li>
                      <li>
                        <a href="<?= $account ?>"
                          >Order History<i class="fas fa-chevron-down"></i
                        ></a>
                      </li>
                      <li>
                        <a href="<?= $account ?>"
                          >Transactions<i class="fas fa-chevron-down"></i
                        ></a>
                      </li>
                      <li>
                        <a href="<?= $account ?>"
                          >Settings<i class="fas fa-chevron-down"></i
                        ></a>
                      </li>
                      <li>
                        <a href="<?= $logout ?>"
                          >Sign Out<i class="fas fa-chevron-down"></i
                        ></a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="menu_trigger_container ml-auto">
                <div
                  class="menu_trigger d-flex flex-row align-items-center justify-content-end"
                >
                  <div class="menu_burger">
                    <div class="menu_trigger_text">menu</div>
                    <div class="cat_burger menu_burger_inner">
                      <span></span><span></span><span></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="page_menu">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="page_menu_content">
              <div class="page_menu_search">
                <form action="#">
                  <input
                    type="search"
                    required="required"
                    class="page_menu_search_input"
                    placeholder="Search for products..."
                  />
                </form>
              </div>
              <ul class="page_menu_nav">
                <li class="page_menu_item">
                  <a href="#">Dashboard<i class="fa fa-angle-down"></i></a>
                </li>
                <li class="page_menu_item">
                  <a href="#"
                    >Purchase Order
                    <span class="badge badge-pill badge-success">BETA</span></a
                  >
                </li>
                <li class="page_menu_item has-children">
                  <a href="<?= $account ?>"
                    >My Account<i class="fa fa-angle-down"></i
                  ></a>
                  <ul class="page_menu_selection">
                    <li>
                      <a href="<?= $account ?>"
                        >Profile<i class="fa fa-angle-down"></i
                      ></a>
                    </li>
                    <li>
                      <a href="<?= $account ?>"
                        >Transactions<i class="fa fa-angle-down"></i
                      ></a>
                    </li>
                    <li>
                      <a href="<?= $account ?>"
                        >Settings<i class="fa fa-angle-down"></i
                      ></a>
                    </li>
                  </ul>
                </li>
                <li class="page_menu_item">
                  <a href="<?= $logout ?>"
                    >Sign Out<i class="fa fa-angle-down"></i
                  ></a>
                </li>
              </ul>
              <div class="menu_contact">
                <div class="menu_contact_item">+254 738 770 186</div>
                <div class="menu_contact_item">
                  <a href="mailto:hello@kwikbasket.com">hello@kwikbasket.com</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import currency from "../util/currency";

export default {
  data: () => ({
    logo: require("@/assets/images/logo.svg"),
    phoneIcon: require("@/assets/images/icons/phone.svg"),
    mailIcon: require("@/assets/images/icons/mail.svg"),
    userIcon: require("@/assets/images/icons/user.svg"),
    wishlistIcon: require("@/assets/images/icons/heart.png"),
    basketIcon: require("@/assets/images/icons/cart.png"),
  }),

  computed: {
    ...mapGetters({
      itemsInBasket: "cart/itemsInBasket",
      basketCost: "cart/basketCost",
    }),

    ...mapState("auth", ["user"]),
    ...mapState("products", ["searchQuery"]),

    query: {
      get() {
        return this.searchQuery;
      },

      set(newQuery) {
          this.$store.state.products.searchQuery = newQuery;
          this.$store.dispatch("products/searchProducts");
      },
    },

    amountInBasket() {
      return currency(this.basketCost, { symbol: "KES " }).format();
    },
  },

  methods: {
    logout() {
      this.$store.dispatch("auth/logout");
    },
  },

  created() {
    this.$store.dispatch("auth/getUserDetails");
  },
};
</script>