const kbApplication = new Vue({
    el: '#kwikbasket-app',

    store: new Vuex.Store({
        state: {
            cartProducts: []
        },

        getters: {
            itemsInCart: state => {
                return state.cartProducts.length;
            },

            basketCost: state => {
                const totalCost = state.cartProducts.reduce(function (prev, { quantity, price }) {
                    return prev + (currency(price).value * quantity);
                }, 0);
                return currency(totalCost, { symbol: 'KES ' }).format();
            }
        },

        mutations: {
            addProductToCart(state, product) {
                state.cartProducts.push(product);
            },

            updateProductQuantity(state, { key, newQuantity }) {
                const index = state.cartProducts.map(item => item.key).indexOf(key);
                state.cartProducts[index].quantity = newQuantity;
            },

            removeProductFromCart(state, product) {
                const index = state.cartProducts.map(item => item.key).indexOf(product.key);
                state.cartProducts.splice(index, 1);
            }
        },

        actions: {
            addProductToCart({ commit }, product) {
                commit('addProductToCart', product);
                const { productId, variationId, storeId, quantity, productNotes, produceType } = product;
                $.ajax({
                    url: 'index.php?path=checkout/cart/add',
                    type: 'POST',
                    data: 'variation_id=' + variationId + '&product_id=' + productId + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1) + '&store_id=' + storeId + '&product_notes=' + productNotes + '&produce_type=' + produceType,
                    dataType: 'json'
                });
            },

            updateProductQuantity({ commit }, { key, newQuantity }) {
                commit('updateProductQuantity', { key, newQuantity });

                // TODO: Refactor
                const product_note = "";
                const produce_type = "";

                $.ajax({
                    url: 'index.php?path=checkout/cart/update',
                    type: 'POST',
                    data: 'key=' + key + '&quantity=' + newQuantity + '&product_note=' + product_note + '&produce_type=' + produce_type,
                    dataType: 'json'
                });
            },

            removeProductFromCart({ commit }, product) {
                commit('removeProductFromCart', product);

                const key = product.key;
                $.ajax({
                    url: 'index.php?path=checkout/cart/remove',
                    type: 'POST',
                    data: 'key=' + key,
                    dataType: 'json'
                });
            }
        }
    }),

    computed: {
        itemsInCart: function() {
            return this.$store.getters.itemsInCart; 
        },

        basketCost: function() {
            return this.$store.getters.basketCost; 
        }
    },

    mounted() {
        $.get('index.php?path=common/cart/getProductsInCart', (products) => {
            this.$store.state.cartProducts = products;
        });
    },
});