<style>
    .cart-product-thumbnail {
        width: 100px;
    }

    .cart-quantity-controls {
        width: 80%;
        position: relative;
    }

    .cart-product-quantity {
        background-color: #cbe5b1;
        border: none;
        height: 34px;
        margin-left: -5px;
        margin-right: -5px;
        outline: none;
        padding: 0;
        text-align: center;
        vertical-align: top;
        width: 40%;
        font-size: 18px;
        line-height: 24px;
    }

    .btn-qty {
        cursor: pointer;
        background-color: #8cc751;
        border: none;
        height: 34px;
        line-height: 20px;
        outline: none;
        width: 30%;
        border-radius: 0px;
        color: #fff;
        font-size: 20px;
        font-weight: 200;
    }
</style>
<div id="mini-cart" class="container">
    <template v-if="cartProducts.length">
        <template v-for="product in cartProducts" :key="product.key">
            <h6 class="model-section-title">
                <?= $product['name'] ?>
            </h6>
            <div class="row modal-row">
                <div class="col-md-12">
                    <img class="cart-product-thumbnail" :src="product.thumb" :alt="product.name">
                    <div>
                        <p class="m-0">{{ product.name }}</p>
                        <p class="m-0">{{ product.price }}</p>
                    </div>
                    <div class="cart-quantity-controls">
                        <button type="button" class="btn btn-success btn-qty" @click="decrementProductQuantity(product)">
                            -
                        </button>
                        <input type="text" disabled="disabled" class="cart-product-quantity" :value="product.quantity">
                        <button type="button" class="btn btn-success btn-qty" @click="incrementProductQuantity(product)">
                            +
                        </button>
                    </div>
                    <button class="btn btn-danger" @click="removeProductFromCart(product)">Delete</button>
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
            incrementProductQuantity(product) {
                const key = product.key;
                const newQuantity = product.quantity + 1;
                kbApplication.$store.dispatch('updateProductQuantity', { key, newQuantity });
            },
            decrementProductQuantity(product) {
                const key = product.key;
                const newQuantity = product.quantity - 1;
                kbApplication.$store.dispatch('updateProductQuantity', { key, newQuantity });
            },
               
            removeProductFromCart(product) {
                kbApplication.$store.dispatch('removeProductFromCart', product);
            }
        },
    });
</script>