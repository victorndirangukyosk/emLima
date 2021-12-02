<?php echo $header; ?>
<div class="dashboard-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="my-order-view-dashboard">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="back-link-block"><a href="<?php echo $continue; ?>"> <span class="back-arrow"><i class="fa fa-long-arrow-left"></i> </span> <?= $text_go_back ?></a></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="my-order-view-content">
                                <div class="my-order">
                                    <div class="list-group my-order-group">
                                        <li class="list-group-item my-order-list-head">
                                            <h2 class="my-order-list-title"><?php echo $wishlist_name; ?></h2>
                                            <span>
                                                (<strong id="total_quantity"><?php echo $total_items; ?></strong> <?= $text_items ?>)
                                            </span>

                                            <!-- <span class="my-order-id-item">
                                              <strong id="total_quantity">(<?php echo $total_quantity; ?></strong> <?= $text_items ?><strong>)</strong>
                                            </span> -->
                                        </li>
                                        <?php $i=0;  foreach ($products as $product) { ?>

                                        <li class="list-group-item" id="product_li_<?php echo $product['product_id'] ?>">

                                            <div class="row" style="display: flex;align-items: center;" >
                                                <div class="col-md-1 checkbox" >
                                                    <?php if($product['is_from_active_store'] && $product['status'] == 1 && $product['category_price_status'] == 1) { ?>
                                                    <label></label><input type="checkbox" name="wishlist_products" value="<?php echo $product['product_store_id'] ?>" data-id="<?php echo $product['product_id'] ?>"/></label>
                                                    <?php } else { ?>
                                                    <label><input type="checkbox" name="wishlist_products" value="<?php echo $product['product_store_id'] ?>" data-id="<?php echo $product['product_id'] ?>" disabled/></label>
                                                    <?php } ?>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="mycart-product-img"><img src="<?= $product['image'] ?>" alt="" class="img-responsive"></div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mycart-product-info">

                                                        <?php if($product['is_from_active_store'] && $product['status'] == 1 && $product['category_price_status'] == 1) { ?>
                                                        <h3> <?php echo $product['name']; ?> </h3>
                                                        <?php } else { ?>
                                                        <h3> <del><?php echo $product['name']; ?></del> </h3>
                                                        <?php } ?>


                                                        <p class="product-info"><span class="small-info"><?php echo $product['unit']; ?></span>
                                                         
                                                        <span class="small-info"><?php echo $product['product_note']; ?></span>
                                                        </p>
                                                        <?php if(!$product['is_from_active_store'] || $product['status'] == 0 || $product['category_price_status'] == 0) { ?>
                                                        <span class="badge badge-danger">
                                                            <?= $text_not_avialable ?>
                                                        </span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                
                                                <?php if($product['is_from_active_store'] && $product['status'] == 1 && $product['category_price_status'] == 1) { ?>
                                                <div class="col-md-3" id="controller-container">

                                                    <div class="inc-dec-quantity" id="<?php echo $product['product_id'] ?>">           
                                                        <input type="button" class="sp-minus fff mini-minus-quantity ddd" data-id="<?php echo $product['product_id'] ?>" data-unit="<?php echo $product['unit'] ?>" data-wishlistid="<?php echo $wishlist_id; ?>" id="minus" value="-">
                                                        <span class="sp-input middle-quantity quntity-input product-count" id="<?php echo 'span'.$product['product_id'] ?>" style="width:50px;">
                                                            <?= $product['quantity']?>        </span>

                                                        <input type="button" class="sp-plus fff mini-plus-quantity ddd" data-id="<?php echo $product['product_id'] ?>" data-unit="<?php echo $product['unit'] ?>" data-wishlistid="<?php echo $wishlist_id; ?>" id="plus" value="+">

                                                    </div>
                                                    <p class="error-msg"></p>

                                                </div>
                                                <?php } ?>

                                                <div class="col-md-2 product-price" >

                                                    <?php if($product['is_from_active_store'] && $product['status'] == 1 && $product['category_price_status'] == 1) { ?>
                                                    
                                                    <?php if(is_array($product['category_price']) && count($product['category_price']) > 0 && $product['category_price']['price'] > 0) { ?>
                                                    <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                                                        <?php echo $this->currency->getCode().' '.$product['category_price']['price']; ?>
                                                    </span>
                                                    <?php } else { ?>
                                                    <?php if ( $product['special_price'] == '0.00' || empty(trim($product['special_price']))) { ?>
                                                    <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>" style="display: none";>
                                                    </span>
                                                    <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                                                        <?php echo $product['price']; ?>
                                                    </span>
                                                    <?php } else { ?>
                                                    <span class="price-cancelled open-popup" data-id="<?= $product['product_store_id'] ?>">
                                                        <?php //echo $product['price']; ?>
                                                    </span>
                                                    <span class="price open-popup" data-id="<?= $product['product_store_id'] ?>">
                                                        <?php echo $product['special_price']; ?>
                                                    </span>
                                                    <?php } } ?>

                                                    <?php } ?>

                                                    <!-- <div class="inc-dec-quantity" id="<?= $product['product_id'] ?>">           
                                                      <input type="button" data-product-id='<?= $product["product_id"] ?>' class="sp-minus fff wishlist-minus-quantity" id="minus" value="-"/>
                                                      <span class="sp-input middle-quantity product-count" id="quantity_<?php echo $product['product_store_id'] ?>" data-product-id='<?= $product["product_id"] ?>'>
                                                          <?= $product['quantity'] ?>
                                                      </span>
                                                      <input type="button" data-product-id='<?= $product["product_id"] ?>' class="sp-plus fff wishlist-plus-quantity" id="plus" value="+" />
                                                    </div> -->

                                                </div>

                                            </div>
                                        </li>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-order-view-sidebar">
                                        <li class="list-group-item my-order-list-head">
                                        <center>
                                            <h2 class="my-order-list-title">
                                                <?= $text_add_to_cart ?>
                                            </h2>

                                            <?php if(!$store_selected) { ?>
                                            <p style="color: red"> <?php echo $text_store_not_selected ?></p>
                                            <?php } ?>

                                        </center>
                                        </li>



                                        <div class="checkout-sidebar">

                                            <div class="row" style="margin-bottom: 8px">


                                                <div class="col-md-16">

                                                    <?php if($store_selected) { ?>
                                                    <button id="selected-add-to-cart" data-id="<?php echo $wishlist_id; ?>" class="btn btn-primary" type="button" data-confirm="This will  add products to basket!!">
                                                        <?php } else { ?>
                                                        <button id="selected-add-to-cart" data-id="<?php echo $wishlist_id; ?>" class="btn btn-primary" type="button" disabled data-confirm="This will add products to basket!!">
                                                            <?php } ?>
                                                            <?= $text_add_selection_to_cart ?></button>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: 8px">


                                                <div class="col-md-16">

                                                    <?php if($store_selected) { ?>
                                                    <button id="list-add-to-cart" data-id="<?php echo $wishlist_id; ?>"  class="btn btn-primary" type="button" data-confirm="This will add products to basket!!">


                                                        <?php } else { ?>
                                                        <button id="list-add-to-cart" data-id="<?php echo $wishlist_id; ?>" class="btn btn-primary" type="button" disabled data-confirm="This will add products to basket!!">
                                                            <?php } ?>

                                                            <?= $text_add_list_to_cart ?> </button>



                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: 8px;">


                                                <div class="col-md-16" style="text-align:center;">

                                                    <a href="<?= $store ?>"  class="btn btn-default"><?= $text_shopping?></a>

                                                </div>
                                            </div>




                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?= $base; ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
</body>

<?php if ($kondutoStatus) { ?>


<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

    var __kdt = __kdt || [];

    var public_key = '<?php echo $konduto_public_key ?>';

    console.log("public_key");
    console.log(public_key);
    __kdt.push({"public_key": public_key}); // The public key identifies your store
    __kdt.push({"post_on_load": false});
    (function () {
        var kdt = document.createElement('script');
        kdt.id = 'kdtjs';
        kdt.type = 'text/javascript';
        kdt.async = true;
        kdt.src = 'https://i.k-analytix.com/k.js';
        var s = document.getElementsByTagName('body')[0];

        console.log(s);
        s.parentNode.insertBefore(kdt, s);
    })();

    var visitorID;
    (function () {
        var period = 300;
        var limit = 20 * 1e3;
        var nTry = 0;
        var intervalID = setInterval(function () {
            var clear = limit / period <= ++nTry;

            console.log("visitorID trssy");
            if (typeof (Konduto.getVisitorID) !== "undefined") {
                visitorID = window.Konduto.getVisitorID();
                clear = true;
            }
            console.log("visitorID clear");
            if (clear) {
                clearInterval(intervalID);
            }
        }, period);
    })(visitorID);


    var page_category = 'order-detail-page';
    (function () {
        var period = 300;
        var limit = 20 * 1e3;
        var nTry = 0;
        var intervalID = setInterval(function () {
            var clear = limit / period <= ++nTry;
            if (typeof (Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent(' page ', page_category); //Programmatic trigger event
                clear = true;
            }
            if (clear) {
                clearInterval(intervalID);
            }
        },
                period);
    })(page_category);

</script>

<?php } ?>


<script type="text/javascript">
    $(document).delegate('.wishlist-minus-quantity', 'click', function () {
        return false;
        $qty_wrapper = $(this).parent().find('.middle-quantity');

        $total_quantity = parseInt($('#total_quantity').html()) - 1;

        $('#total_quantity').html($total_quantity);

        $qty = parseInt($qty_wrapper.html()) - 1;
        //$product_id = $(this).attr('data-id');    

        $this = $(this);
        if ($qty > 0) {
            $qty_wrapper.html($qty);

            //update  wishlist product count 
            var productId = $(this).attr('data-product-id');

            console.log('remove pro');
            console.log(productId);

            $.ajax({
                url: 'index.php?path=account/wishlist/updateWishlistProduct',
                type: 'post',
                data: {
                    wishlist_id: '<?php echo $wishlist_id ?>',
                    product_id: productId,
                    quantity: $qty,
                },
                dataType: 'json',
                success: function (json) {
                }
            });

        } else {
            //remove wishlist product ajax call
            var productId = $(this).attr('data-product-id');

            var string = '#product_li_' + productId;

            console.log('remove pro');
            console.log(productId);

            $.ajax({
                url: 'index.php?path=account/wishlist/deleteWishlistProduct',
                type: 'post',
                data: {
                    wishlist_id: '<?php echo $wishlist_id ?>',
                    product_id: productId,
                },
                dataType: 'json',
                success: function (json) {
                    console.log(json);
                    $(string).remove();
                }
            });

        }
    });
    $(document).delegate('.wishlist-plus-quantity', 'click', function () {
        return false;
        $qty_wrapper = $(this).parent().find('.middle-quantity');

        $qty = parseInt($qty_wrapper.html()) + 1;

        $total_quantity = parseInt($('#total_quantity').html()) + 1;

        console.log($total_quantity);

        $('#total_quantity').html($total_quantity);

        $this = $(this);
        if ($qty > 0) {
            $qty_wrapper.html($qty);

            //update  wishlist product count 
            var productId = $(this).attr('data-product-id');

            console.log('add pro quantity');
            console.log(productId);

            $.ajax({
                url: 'index.php?path=account/wishlist/updateWishlistProduct',
                type: 'post',
                data: {
                    wishlist_id: '<?php echo $wishlist_id ?>',
                    product_id: productId,
                    quantity: $qty,
                },
                dataType: 'json',
                success: function (json) {
                }
            });

        } else {
            //remove wishlist product ajax call
        }
    });

    $(document).delegate('#selected-add-to-cart', 'click', function () {
        var choice = confirm($(this).attr('data-confirm'));
        if (choice == true) {
            var wishlist_id = $(this).attr('data-id');
            var checkedNum = $('input[name="wishlist_products[]"]:checked').length;
            console.log(wishlist_id);
            console.log(checkedNum);
            var val = [];
            if (!checkedNum) {
                $(':checkbox:checked').each(function (i) {
                    val[i] = $(this).data("id");
                });
                console.log(val);
            }
            if (val.length == 0) {
                alert('Please select atleast one product!');
                return false;
            }

            $.ajax({
                url: 'index.php?path=account/wishlist/addWishlistProductToCartByProduct',
                type: 'post',
                data: {'products': val, 'wishlist_id': wishlist_id},
                dataType: 'json',
                cache: false,
                async: true,
                success: function (json) {
                    console.log(json.status);
                    if (json.location != null && json.status == 'success') {
                        console.log(json.location);
                        var timer = setTimeout(function () {
                            window.location.href = json.location;
                        }, 1000);
                        return false;
                        //location = json.redirect;
                        //location = location;
                    }
                }
            });
        }
    });

    $(document).delegate('#list-add-to-cart', 'click', function (e) {

        e.preventDefault();
        var choice = confirm($(this).attr('data-confirm'));

        if (choice) {
            console.log("addWishlisttocart click");
            console.log($(this).attr('data-id'));
            $('#addWishlisttocart').html('Wait...');
            var orderId = $(this).attr('data-id');
            $.ajax({
                url: 'index.php?path=account/wishlist/addWishlistProductToCart',
                type: 'post',
                data: {
                    wishlist_id: $(this).attr('data-id')
                },
                dataType: 'json',
                success: function (json) {
                    console.log(json);

                    //setTimeout(function () {
                     //   window.location.reload(false);
                   // }, 1000);

                   if (json.location != null && json.status == 'success') {
                        console.log(json.location);
                        var timer = setTimeout(function () {
                            window.location.href = json.location;
                        }, 1000);
                        return false;
                        //location = json.redirect;
                        //location = location;
                    }
                }
            });
        }
    });

    $(document).delegate('#plus, #minus', 'click', function (e) {

        e.preventDefault();

        var product_id = $(this).attr('data-id');
        var wishlist_id = $(this).attr('data-wishlistid');
        console.log($(this).attr('data-id'));
        console.log($(this).attr('id'));
        console.log($(this).attr('data-unit'));
        console.log($("#" + $(this).attr('data-id')).text().replace(/\s/g, ''));
        console.log($(this).attr('data-wishlistid'));

        var quantity = $("#" + $(this).attr('data-id')).text().replace(/\s/g, '');

        if ($(this).attr('id') == 'minus') {
            if ($(this).attr('data-unit') == 'Kg' || $(this).attr('data-unit') == 'Kgs')
            {
                var qty = parseFloat(quantity) - 0.5;
                console.log(qty);
            } else {
                var qty = parseFloat(quantity) - 1;
                console.log(qty);
            }
        }

        if ($(this).attr('id') == 'plus') {
            if ($(this).attr('data-unit') == 'Kg' || $(this).attr('data-unit') == 'Kgs')
            {
                var qty = parseFloat(quantity) + 0.5;
                console.log(qty);
            } else {
                var qty = parseFloat(quantity) + 1;
                console.log(qty);
            }
        }
        if (qty < 0) {
            alert('Invalid Quantity!');
            return false;
        }
        $.ajax({
            url: 'index.php?path=account/wishlist/updateWishlistProduct',
            type: 'post',
            data: {wishlist_id: wishlist_id, product_id: product_id, quantity: qty},
            dataType: 'json',
            beforeSend: function () {
                //$('#cart > button').button('loading');
            },
            complete: function () {
                //$('#cart > button').button('reset');
            },
            success: function (json) {
                if (json.status == true && json.delete == false) {
                    console.log('Quantity Updated');
                    $("#span" + product_id).text(qty);

                    console.log($("#total_quantity").text().replace(/\s/g, ''));
                    $("#total_quantity").text(json.total_quantity);
                    return false;
                } if(json.status == true && json.delete == true) {
                  console.log('reload page');
                  window.location.reload();
                  return false;
                } else {
                    alert('Please try again later!');
                    return false;
                }
                console.log(json);
            }
        });
    });
</script>

<style>
.my-order-list-title{
    font-family: Poppins;
font-style: normal;
font-weight: 600;
font-size: 18px;
line-height: 27px;
color: #FA8700;
}
</style>
</html>
