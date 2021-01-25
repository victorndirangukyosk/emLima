<style>
    .cart-product-thumbnail {
        width: 100px;
    }

    .cart-product-quantity {
        background-color: #cbe5b1;
        border: none;
        height: 34px;
        outline: none;
        padding: 0;
        text-align: center;
        vertical-align: top;
        width: 40px;
        font-size: 18px;
        line-height: 24px;
    }

    .cart-quantity-controls {
        width: min-content;
    }

    .btn-qty {
        padding: 0;
        cursor: pointer;
        background-color: #8cc751;
        border: none;
        height: 34px;
        line-height: 20px;
        outline: none;
        width: 30px;
        color: #fff;
        font-size: 20px;
    }

    .btn-minus {
        border-radius: .7rem 0 0 .7rem;
    }

    .btn-plus {
        border-radius: 0 .7rem .7rem 0;
    }

    .cart-remove-button {
        cursor: pointer;
    }

</style>

<div id="mini-cart" class="container">
    <template v-if="cartProducts.length">
        <template v-for="product in cartProducts" :key="product.key">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="model-section-title">{{ product.name }}</h6>
                    <h5 class="mt-2 text-success"><strong>{{ productTotalPrice(product) }}</strong></h5>
                </div>
                <div class="cart-remove-button" @click="removeProductFromCart(product)">
                    <img src="<?= $base;?>front/ui/theme/metaorganic/assets/images/icons/remove.svg" alt="Remove from cart">
                </div>
            </div>
            
            
            <div class="row modal-row">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap justify-content-between">
                        <img class="cart-product-thumbnail" :src="product.thumb" :alt="product.name">
                        <div class="cart-quantity-controls d-flex flex-column align-items-center justify-content-around">
                            <span class="mb-2">{{ product.price }}</span>
                            <div class="d-flex flex-row justify-content-center">
                                <button type="button" class="btn btn-success btn-minus btn-qty" @click="decrementProductQuantity(product)">
                                    -
                                </button>
                                <input type="text" disabled="disabled" class="cart-product-quantity" :value="product.quantity">
                                <button type="button" class="btn btn-success btn-plus btn-qty" @click="incrementProductQuantity(product)">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </template>
    <h6 v-else class="text-muted text-center mt-5">No products in your basket</h6>
</div>

<script>
    new Vue({
        el: '#mini-cart',
        data() {
            return {
                cartProducts: kbApplication.$store.state.cartProducts
            }
        },
        methods: {
            productTotalPrice({ price, quantity }) {
                const total = currency(price).value * quantity;
                return currency(total, { symbol: 'KES ' }).format()
            },

            incrementProductQuantity(product) {
                const key = product.key;
                const newQuantity = parseFloat(product.quantity) + 1;
                kbApplication.$store.dispatch('updateProductQuantity', { key, newQuantity });
            },

            decrementProductQuantity(product) {
                const key = product.key;
                const newQuantity = parseFloat(product.quantity) - 1;
                
                if(newQuantity == 0) {
                    kbApplication.$store.dispatch('removeProductFromCart', product);
                } else {
                    kbApplication.$store.dispatch('updateProductQuantity', { key, newQuantity });
                }
            },
               
            removeProductFromCart(product) {
                kbApplication.$store.dispatch('removeProductFromCart', product);
            }
        },
    });
</script>