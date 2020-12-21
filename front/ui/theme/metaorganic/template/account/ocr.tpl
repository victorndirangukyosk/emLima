<?php echo $header; ?>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
    integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g=="
    crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
    integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
    crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css"
    href="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/css/iziToast.min.css">
<script src="<?= $base; ?>front/ui/theme/metaorganic/assets_landing_page/js/iziToast.min.js" async
    defer="defer"></script>

<div class="container">
    <div class="row" style="margin-bottom: 40px;">
        <div class="col-md-12" style="display: flex;justify-content: center;align-items: center;">
            <div class="header__search-bar-wrapper">
                <div id="search-form-wrapper" class="header__search-bar search-form-wrapper">
                    <div class="header__search-title">
                        Search
                        <div class="header__mobile-search-close j-mobile-close-search-trigger"></div>
                    </div>

                    <form id="search-form-form"
                        class="search-form c-position-relative search-form--switch-category-position" action="#"
                        method="get">
                        <ul class="header__search-bar-list header__search-bar-item--before-keyword-field">

                            <li
                                class="header__search-bar-item header__search-bar-item--category search-category-container">
                                <div>
                                    <select class="form-control" id="selectedCategory">
                                        <option value="">- Select categories-</option>
                                        <option value="1359">Fruits</option>
                                        <option value="1">Vegetables</option>
                                        <option value="1369">Herbs</option>
                                        <option value="1370">Leafy Vegetables</option>
                                        <option value="1371">Asian Vegetables</option>
                                        <option value="1373">Vegan</option>

                                    </select>
                                </div>
                            </li>
                            <li class="header__search-bar-item header__search-bar-item--location search-location-all">
                                <div class="header__search-location search-location">
                                    <i class="fa fa-map-marker header__search-location-icon" aria-hidden="true"></i>

                                    <!-- SuggestionWidget  start -->
                                    <div id="search-area-wrp" class="c-sggstnbx header__search-input-wrapper">

                                        <div class="input-group">
                                            <input type="text" name="product_name" id="product_name"
                                                class="header__search-input zipcode-enter"
                                                placeholder="Search for your product" autocomplete="off">
                                            <ul class="dropdown-menu search-dropdown"></ul>
                                            <span class="input-group-btn">
                                                <div class="resp-searchresult">
                                                    <div></div>
                                                </div>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 20px;">
        <div id="ocr-errors" class="col-md-12"></div>
    </div>
    <div class="row" id="ocr-input">
        <div class="col-md-12">
            <form method="POST" enctype="multipart/form-data" id="po_form">
                <input type="hidden" name="store_id" value=<?=$store_id ?>>
                <input type="hidden" name="customer_id" value=<?=$customer_id ?>>
                <input type="hidden" name="customer_category" value=<?=$customer_category ?>>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="delivery_address">Delivery Address</label>
                        <select id="delivery_address" name="delivery_address" class="form-control" required>
                            <?php foreach($addresses as $address_id => $address) { ?>
                            <option value=<?=$address_id ?>>
                                <?= $address ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="delivery_time">Delivery Date & Time</label>
                        <input id="delivery_time" name="delivery_time" class="form-control" type="text"
                            placeholder="Click to enter date and time" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="po">Purchase Order Document</label>
                        <input id="po" name="po" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input type="submit" id="submit" name="sumbit" class="btn" value="Process Order">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;" id="ocr-assumptions">
    </div>
</div>

<!--Cart HTML Start-->
<div class="store-cart-panel">
    <div class="modal right fade" id="store-cart-side" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="cart-panel-content">
                </div>
                <div class="modal-footer">
                    <!-- <p><?= isset($text_verify_number) ? $text_verify_number : '' ?></p> -->
                    <a href="<?php echo $checkout; ?>" id="proceed_to_checkout">

                        <button type="button" class="btn btn-primary btn-block btn-lg" id="proceed_to_checkout_button">
                            <span class="checkout-modal-text">
                                <?= isset($text_proceed_to_checkout) ? $text_proceed_to_checkout : '' ?>
                            </span>
                            <div class="checkout-loader" style="display: none;"></div>

                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!--Cart HTML End-->
<script>
    jQuery('#delivery_time').datetimepicker({
        format: 'd.m.Y H:i',
        minDate: 0,
        minTime: '08:00',
        maxTime: '16:00'
    });

    $(function () {
        $('#po_form').on('submit', function (e) {
            e.preventDefault();

            const submitButton = $('#submit');
            submitButton.val('PLEASE WAIT');
            submitButton.toggleClass('disabled');

            var data = new FormData();
            $.each($("#po_form").serializeArray(), function (key, input) {
                data.append(input.name, input.value);
            });
            data.append('document', ($('input[name="po"]')[0].files[0]));

            $.ajax({
                url: "http://localhost:4000/v1/ocr/po",
                // url: "https://ocr.kwikbasket.com/v1/ocr/po",
                type: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    submitButton.val('Process Order');
                    submitButton.toggleClass('disabled');

                    $('#ocr-input').remove();
                    $('form#po_form').trigger('reset');

                    if (response.products.length == 0 && response.assumptions.length == 0 && response.errors.length == 0) {
                        displayErrorParsingDocument();
                        return;
                    }

                    for (const orderItem of response.errors) {
                        displayUnrecognizedProduct(orderItem);
                    }

                    for (const orderItem of response.products) {
                        cart.add(orderItem.product_id, orderItem.quantity, 0, 75, '', null);
                    }

                    for (const orderItem of response.assumptions) {
                        cart.add(orderItem.product_id, orderItem.quantity, 0, 75, '', null);
                        displayAssumedProduct(orderItem);
                    }

                    iziToast.success({
                        position: 'topRight',
                        title: 'Success',
                        message: `Adding ${response.products.length + response.assumptions.length} products to the cart!`
                    });
                },
                error: function (error) {
                    submitButton.val('Process Order');
                    submitButton.toggleClass('disabled');
                    displayErrorParsingDocument();
                }
            });

            function displayAssumedProduct(orderItem) {
                $('#ocr-assumptions').append(`
                    <div class="col-md-4 form-group">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h3><strong>${orderItem.original_product}</strong> assumed to be <strong>${orderItem.product}</strong></h3>
                            </div>
                        </div>  
                    </div>
                `);
            }

            function displayUnrecognizedProduct(orderItem) {
                $('#ocr-errors').append(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        Couldn't add <strong>${orderItem.quantity} units</strong> of <strong>${orderItem.product}</strong> to the cart. Please check the PO document and add the product manually.
                    </div>
                `);
            }

            function displayErrorParsingDocument() {
                iziToast.error({
                    position: 'topRight',
                    title: 'Oops',
                    message: 'We couldn\'t process the document'
                });
                $('#ocr-errors').append(`
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        Sorry, we couldn't process the document. Contact support.
                    </div>
                `);
            }
        });
    });
</script>