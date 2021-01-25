const kbApplication = new Vue({
    el: '#kwikbasket-app',
    store: new Vuex.Store({
        state: {
            cartProducts: []
        },

        mutatations: {
            addProductToCart(state, product) {
                state.cartProducts.push(product);
            },

            removeProductFromCart(state, product) {
                state.cartProducts.splice(this.cartProducts.indexOf(product), 1);
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
    mounted() {
        // Get products in cart
    },
});