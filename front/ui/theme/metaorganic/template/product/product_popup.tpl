<style>
    .mt-100 {
        margin-top: 150px
    }

    .modal-content {
        border-radius: 0.7rem
    }

    @media(width:1024px) {
        .modal-dialog {
            max-width: 700px
        }
    }

    .modal-title {
        text-align: center;
        font-size: 3vh;
        font-weight: bold;
        margin-left: auto;
    }

    .modal-header {
        border-bottom: none;
        text-align: center;
        padding-bottom: 0
    }

    .model-section-title {
        color: #2aa249;
        margin-top: 2vh;
        margin-bottom: 0;
        font-size: 2vh
    }

    .modal-body {
        padding: 2vh;
        position: relative;
    }

    .price-container {
        display: none;
        position: absolute;
        top: 60px;
        right: 40px;
        background: #916042;
        padding: 4px 16px;
        transform: rotate(20deg);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 4px;
    }

    .product-modal-price {
        color: #fff;
        margin: 0;
    }

    .product-thumbnail {
        width: 35%
    }

    .modal-footer {
        border-top: none;
        justify-content: center;
        padding-top: 0
    }

    .modal-row {
        border-bottom: 1px solid rgba(0, 0, 0, .2);
        padding: 2vh 0 2vh 0;
        justify-content: space-between;
        flex-wrap: unset;
        margin: 0
    }

    #product-quantity::placeholder {
        text-align: center;
    }

    .btn-cta-add {
        background-color: #2aa249;
        border-color: #2aa249;
        color: white;
        width: 70%;
        padding: 2vh;
        margin-top: 0;
        border-radius: 0 0.7rem 0.7rem 0;
        box-shadow: none
    }

    .openmodal {
        background-color: white;
        color: black;
        width: 30vw
    }

    :-moz-any-link:focus {
        outline: none
    }

    button:active {
        outline: none
    }

    button:focus {
        outline: none
    }

    .btn:focus {
        box-shadow: none
    }

    .variations-container input {
        display: none;
    }

    .variation-pill {
        display: block;
        border: 1.5px solid #2aa249;
        border-radius: 16px;
        padding: 4px 12px;
        color: #2aa249;
        text-align: center;
        cursor: pointer;
    }

    [id^='variation-selector-'] input:checked+span {
        background: #2aa249;
        color: #fff;
    }

    [id^='variation-selector-'] input:disabled+span {
        opacity: .65;
    }
</style>

<div class="modal fade" id="product-details-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">
                    <?= $product['product_info']['name'] ?>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center align-items-center">
                            <img class="img-fluid product-thumbnail" src="<?= $product['thumb']; ?>">
                        </div>
                    </div>
                    <h6 class="model-section-title"><?= $product['qty_in_cart'] ? 'Ordered In' : 'Available In'; ?></h6>
                    <div class="row modal-row">
                        <div class="col-md-12 variations-container">
                            <?php foreach($product['variations'] as $variation) { ?>
                            <label id="variation-selector-<?= $variation['variation_id'] ?>">
                                <input
                                    data-price="<?php echo isset($variation['special_price']) ? $variation['special_price'] : ''; ?>"
                                    data-quantity="<?php echo isset($variation['qty_in_cart']) ? $variation['qty_in_cart'] : ''; ?>"
                                    data-key="<?php echo isset($variation['key']) ? $variation['key'] : ''; ?>"
                                    data-product-id="<?= $variation['product_id'] ?>"
                                    data-variation-id="<?= $variation['variation_id'] ?>" 
                                    name="variation" type="radio"
                                    <?= $product['qty_in_cart'] && $product['product_info']['product_store_id'] == $variation['variation_id'] ? 'checked' : ''; ?>
                                    <?= $product['qty_in_cart'] ? 'disabled' : ''; ?>
                                    >
                                <span class="variation-pill">
                                    <?=  $variation['unit'] ?>
                                </span>
                            </label>
                            <?php } ?>
                        </div>
                    </div>
                    <h6 class="model-section-title">Product Notes (Optional)</h6>
                    <div class="row modal-row">
                        <div class="col-md-12 px-0">
                            <textarea class="form-control" id="product-notes"
                                placeholder="Tell us how you'd like this product e.g Big, Ripe, Peeled, etc."
                                rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="price-container">
                    <p class="product-modal-price"></p>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input id="product-quantity" class="form-control" placeholder="Quantity">
                                <input type="button" class="btn btn-cta-add"
                                data-store-id="<?= $product['store_id'] ?>"
                                data-action="<?= $product['qty_in_cart'] ? 'update' : 'add'; ?>"
                                data-key="<?= $product['key'] ?>"
                                data-product-id="<?= $product['product_info']['product_id'] ?>"
                                data-variation-id=""
                                value="<?= $product['qty_in_cart'] ? 'Update Basket' : 'Add To Basket'; ?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('#product-quantity').keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $('input[type=radio][name=variation]').change(function () {
        if ($('.price-container').is(":hidden")) $('.price-container').show();
        
        const price = $(this).attr('data-price');
        $('.product-modal-price').html(`KES ${price}`);

        const variationId = $(this).attr('data-variation-id');
        $('.btn-cta-add').attr('data-variation-id', variationId);
    });

    $('input[type=radio][name=variation], #product-quantity')
        .on('change mousedown mouseup keyup keydown', function () {
            if ($('input[type=radio][name=variation]').is(":checked")
                && $('#product-quantity').val() != '') {
                $('.btn-cta-add').removeAttr('disabled');
            } else {
                $('.btn-cta-add').attr('disabled', 'disabled');
            }
        });

    $('.btn-cta-add').click(function() {
        const storeId = $(this).attr('data-store-id');
        const productId = $(this).attr('data-product-id');
        const variationId = $(this).attr('data-variation-id');
        const quantity = $('#product-quantity').val();
        const productNotes = $('#product-notes').val();

        const action = $(this).attr('data-action');
        const key = $(this).attr('data-key');

        // TODO: Update cart quantity
        // TODO: Add produce type selector (last null parameter)
        cart.add(productId, variationId, storeId, quantity, productNotes, "");

        $('#product-details-popup').modal('hide');
    });
</script>