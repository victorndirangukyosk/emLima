const kbApplication = new Vue({
    el: '#kwikbasket-app',

    data() {
        return {
            selectedProduct: this.getInitialPopupState(),
            searchQuery: '',
            apiSearchResults: [],
        }
    },

    store: new Vuex.Store({
        state: {
            productCategories: [],
            cartProducts: []
        },
    
        getters: {
            itemsInCart: state => {
                return state.cartProducts.length;
            },
    
            basketCost: state => {
                return state.cartProducts.reduce(function (prev, { quantity, price }) {
                    return prev + (currency(price).value * quantity);
                }, 0);
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
                const { product_id, variation_id, store_id, quantity, productNotes, produceType } = product;
                $.ajax({
                    url: 'index.php?path=checkout/cart/add',
                    type: 'POST',
                    data: 'variation_id=' + variation_id + '&product_id=' + product_id + '&quantity=' + quantity + '&store_id=' + store_id + '&product_notes=' + productNotes + '&produce_type=' + produceType,
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
        isSearchActive: function () {
            return this.searchQuery.length > 0;
        },

        searchResults: function () {
            let results = new Set();
            return this.$store.state.productCategories
                .flatMap(category => category.products)
                .concat(this.apiSearchResults)
                .filter(product => product.name.toLowerCase().indexOf(this.searchQuery.toLowerCase()) >= 0)
                .filter(product => {
                    let key = product.product_id;
                    return results.has(key) ? false : results.add(key);
                });
        },

        productCategories: function () {
            return this.$store.state.productCategories;
        },

        itemsInCart: function () {
            return this.$store.getters.itemsInCart;
        },

        basketCost: function () {
            return this.formatCurrency(this.$store.getters.basketCost);
        },

        cartProducts: function () {
            return this.$store.state.cartProducts;
        }
    },

    mounted() {
        $.get('index.php?path=common/home/getCategoriesWithProducts', (categories) => {
            this.$store.state.productCategories = categories;
        });

        $.get('index.php?path=common/cart/getProductsInCart', (products) => {
            this.$store.state.cartProducts = products;
        });

        $('#product-details-popup').on('hide.bs.modal', () => {
            this.selectedProduct = this.getInitialPopupState();
        });
    },

    methods: {
        showProductPopup(product) {
            this.selectedProduct.info = product;
        },

        getInitialPopupState() {
            return {
                info: {},
                popup: {
                    variation: {},
                    quantity: '',
                    productNotes: '',
                    produceType: '',
                    isValidData: false
                }
            }
        },

        addProductToCart() {
            this.$store.dispatch('addProductToCart', {
                ...this.selectedProduct.info,
                variation_id: this.selectedProduct.popup.variation.variation_id,
                key: this.selectedProduct.popup.variation.key,
                price: this.selectedProduct.popup.variation.price,
                quantity: this.selectedProduct.popup.quantity,
                productNotes: this.selectedProduct.popup.productNotes,
                produceType: this.selectedProduct.popup.produceType
            });
            $('#product-details-popup').modal('hide');
        },

        openMiniCart() {
            $('#mini-cart-panel').modal('show');
        },

        formatCurrency(amount) {
            return currency(amount, { symbol: 'KES ' }).format()
        },

        productTotalPrice({ price, quantity }) {
            const total = currency(price).value * quantity;
            return this.formatCurrency(total);
        },

        incrementProductQuantity(product) {
            const key = product.key;
            const newQuantity = (parseFloat(product.quantity) + 1).toFixed(1);
            this.$store.dispatch('updateProductQuantity', { key, newQuantity });
        },

        decrementProductQuantity(product) {
            const key = product.key;
            const newQuantity = (parseFloat(product.quantity) - 1).toFixed(1);

            if (newQuantity <= 0) {
                this.$store.dispatch('removeProductFromCart', product);
            } else {
                this.$store.dispatch('updateProductQuantity', { key, newQuantity });
            }
        },

        removeProductFromCart(product) {
            this.$store.dispatch('removeProductFromCart', product);
        },

        searchProduct:
            _.debounce(function () {
                $.ajax({
                    url: `index.php?path=product/search/product_search&filter_name=${encodeURIComponent(this.searchQuery)}`,
                    dataType: 'json',
                    success: (data) => {
                        this.apiSearchResults = data;
                    },
                    error: (error) => {
                        this.apiSearchResults = [];
                    }
                });
            }, 500)
    },

    watch: {
        searchQuery: function () {
            if (this.isSearchActive) {
                this.searchProduct();
            }
        },

        selectedProduct: {
            handler(selectedProduct) {
                if (Object.keys(selectedProduct).length) {
                    $('#product-details-popup').modal('show');

                    const { variation, quantity } = selectedProduct.popup;
                    this.selectedProduct.popup.quantity = quantity.replace(/[^0-9\.]/g, '');

                    if (quantity > 0 && variation != "") {
                        this.selectedProduct.popup.isValidData = true;
                    } else {
                        this.selectedProduct.popup.isValidData = false;
                    }
                }
            },
            deep: true
        },
    }
});

// Block page reload until all pending XHR requests complete
let hasPendingRequest = false;
$(document).ajaxStart(() => hasPendingRequest = true);
$(document).ajaxStop(() => hasPendingRequest = false);

window.onbeforeunload = function () {
    if (hasPendingRequest) {
        return 'If you navigate away from this page you will lose your unsaved changes.Give us a Kwik minute and try again';
    }
}