<div id="mini-cart-panel" class="modal fixed-left fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Basket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="mini-cart" class="container">
                    <template v-if="cartProducts.length">
                        <template v-for="product in cartProducts" :key="product.key">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="model-section-title">{{ product.name }}</h6>
                                    <h5 class="mt-2 text-success"><strong>{{ productTotalPrice(product) }}</strong></h5>
                                </div>
                                <div class="cart-remove-button" @click="removeProductFromCart(product)">
                                    <img src="<?= $base;?>front/ui/theme/kwikbasket/assets/images/icons/remove.svg"
                                        alt="Remove from cart">
                                </div>
                            </div>

                            <div class="row modal-row">
                                <div class="col-md-12">
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <img class="cart-product-thumbnail" :src="product.thumb" :alt="product.name">
                                        <div
                                            class="cart-quantity-controls d-flex flex-column align-items-center justify-content-around">
                                            <span class="mb-2">{{ product.price }}</span>
                                            <div class="d-flex flex-row justify-content-center">
                                                <button type="button" class="btn btn-success btn-minus btn-qty"
                                                    @click="decrementProductQuantity(product)">
                                                    -
                                                </button>
                                                <input type="text" disabled="disabled" class="cart-product-quantity"
                                                    :value="product.quantity">
                                                <button type="button" class="btn btn-success btn-plus btn-qty"
                                                    @click="incrementProductQuantity(product)">
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
            </div>
            <div v-if="itemsInCart" class="modal-footer">
                <a href="<?= $checkout ?>"class="btn btn-success btn-cta">Checkout ({{ basketCost }})</a>
            </div>
        </div>
    </div>
</div>