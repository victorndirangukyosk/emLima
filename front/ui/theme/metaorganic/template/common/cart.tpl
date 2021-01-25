<style>
.cart-product-thumbnail {
    width: 100px;
}

</style>
<div id="mini-cart" class="container">
    <template v-if="cartProducts.length">
        <template v-for="product in cartProducts" :key="product.key">
            <h6 class="model-section-title"><?= $product['name'] ?></h6>
            <div class="row modal-row">
                <div class="col-md-12">
                    <img class="cart-product-thumbnail" :src="product.thumb" :alt="product.name">
                   <div>
                        <span>{{ product.name }}</span>
                        <span>{{ product.price }}</span>
                        <span>{{ product.quantity }}</span>
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
            removeProductFromCart(product) {
                kbApplication.$store.dispatch('removeProductFromCart', product);
            }
        },
    });
</script>