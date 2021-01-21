<div class="container">
    <?php if($products) { ?>
    <?php foreach ($arr as $key=> $products) { ?>
    <?php foreach ($products as $product) { ?>
    <h6 class="model-section-title"><?= $product['name'] ?></h6>
    <div class="row modal-row">
        <div class="col-md-12 d-flex">
           <img src="<?= $product['thumb'] ?>" alt="<?= $product['name'] ?>">
           <div class="d-flex px-3 py-3">
                <p><?= $product['price'] ?></p>
           </div>
        </div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {

    });




    $('.delete-item').on('click', function () {

        var key = $(this).attr('data-value');
        //alert(key); 

        $product_id = $(this).attr('product_id');
        $.ajax({
            url: 'index.php?path=checkout/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            beforeSend: function () {
                //$('#cart > button').button('loading');
            },
            complete: function () {
                //$('#cart > button').button('reset');
            },
            success: function (json) {
                // Hide for qnty Box
                /*$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
                 $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
                 $qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
                */
                $('#flag-qty-id-' + $product_id + '-0').html('');
                $('#flag-qty-id-' + $product_id + '-0').css("display", "none");
                //reflact changes in list 
                $('#action_' + json['product_id'] + '[data-variation-id="' + json['variation_id'] + '"] .middle-quantity').html(json['quantity']);

                if (json['location'] == 'cart-checkout') {
                    location = 'index.php?path=checkout/cart';
                } else {

                    //update total count for mobile 
                    $('.shoppingitem-fig').html(json['count_products']);

                    $('#cart').load('index.php?path=common/cart/info');

                    $('.cart-panel-content').load('index.php?path=common/cart/newInfo');

                    $('.cart-count').html(json['count_products'] + ' ITEMS IN CART ');
                    $('.cart-total-amount').html(json['total_amount']);
                }

                $.ajax({
                    url: 'index.php?path=common/home/cartDetails',
                    type: 'post',
                    dataType: 'json',

                    success: function (json) {
                        console.log(json);
                        alert('Item deleted successfully');


                        for (var key in json['store_note']) {
                            //alert("User " + data[key] + " is #" + key); // "User john is #234"
                            $('.store_note' + key).html(json['store_note'][key]);

                            console.log(json['store_note'][key]);
                        }

                        if (json['status']) {
                            console.log("yesz");
                            console.log(text);
                            $("#proceed_to_checkout").removeAttr("disabled");
                            $("#proceed_to_checkout").attr("href", json['href']);
                            //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
                            //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

                            $("#proceed_to_checkout_button").css({ 'background-color': '', 'border-color': '' });
                            $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                            $('.checkout-loader').hide();

                        } else {
                            console.log("no frm jsz");
                            $("#proceed_to_checkout").attr("disabled", "disabled");
                            $("#proceed_to_checkout").removeAttr("href");
                            //$("#proceed_to_checkout_button").html(json['amount']);
                            //$('.checkout-modal-text').html(json['amount']);
                            $('.checkout-loader').hide();
                            $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                            $("#proceed_to_checkout_button").css('background-color', '#ccc');
                            $("#proceed_to_checkout_button").css('border-color', '#ccc');



                        }


                    }
                });




            }
        });

    });

</script>