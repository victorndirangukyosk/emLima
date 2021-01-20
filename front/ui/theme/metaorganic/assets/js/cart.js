var cart = {
	'add': function (productId, variationId, storeId, quantity, productNotes, produceType) {
		$.ajax({
			url: 'index.php?path=checkout/cart/add',
			type: 'POST',
			data: 'variation_id=' + variationId + '&product_id=' + productId + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1) + '&store_id=' + storeId + '&product_notes=' + productNotes + '&produce_type=' + produceType,
			// data: {
            //     product_id: productId,
            //     variation_id: variationId,
            //     store_id: storeId,
            //     quantity: quantity,
            //     product_notes: productNotes,
            //     produce_type: produceType
            // },
			dataType: 'json',
			success: function (json) {
				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('.cart_items_count').html(json['count_products']);
                    $('.cart_price').html(json['total_amount']);

                    $(`#${productId}-product-quantity`).html(quantity);
                    $(`#${productId}-product-quantity`).css("display","block");
				}
			}
		});
    },

	'update': function (key, quantity, product_note = null, produce_type = null) {

		var text = $('.checkout-modal-text').html();
		$('.checkout-modal-text').html('');
		$('.checkout-loader').show();

		console.log("cart update api js file");
		$.ajax({
			url: 'index.php?path=checkout/cart/update',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1) + '&product_note=' + product_note + '&produce_type=' + produce_type,//+ '&ripe=' +ripe
			dataType: 'json',
			async: false,
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
				//reflact changes in list 
				$('#action_' + json['product_id'] + '[data-variation-id="' + json['variation_id'] + '"] .middle-quantity').html(json['quantity']);

				if (json['location'] == 'cart-checkout') {
					location = 'index.php?path=checkout/cart';
				} else {

					//update total count for mobile 
					$('.shoppingitem-fig').html(json['count_products']);

					$('#cart').load('index.php?path=common/cart/info');

					$('.cart-panel-content').load('index.php?path=common/cart/newInfo');

					$('.cart-count').html(json['count_products'] + " ITEMS IN CART");
					$('.cart-total-amount').html(json['total_amount']);
				}

				$.ajax({
					url: 'index.php?path=common/home/cartDetails',
					type: 'post',
					dataType: 'json',

					success: function (json) {
						console.log(json);

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


	},
	'remove': function (key) {

		var text = $('.checkout-modal-text').html();
		$('.checkout-modal-text').html('');
		$('.checkout-loader').show();

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
				//$('.checkout-modal-text').html(text);
			},
			success: function (json) {

				console.log(json);
				console.log("remove cart");
				//$('#action_'+json['product_store_id']+' .add-cart-btn').css('display','block');
				$('#action_' + json['product_store_id'] + ' p.error-msg').html('');
				$('#action_' + json['product_store_id'] + ' .add-cart-btn').parent().parent().find('.info').css('display', 'none');

				if (json['location'] == 'cart-checkout') {
					location = 'index.php?path=checkout/cart';
				} else {
					//update total count for mobile 
					/*start*/
					$('.cart-panel-content').load('index.php?path=common/cart/newInfo');

					$('.cart-count').html(json['count_products'] + " ITEMS IN CART");
					$('.cart-total-amount').html(json['total_amount']);

					/*end*/

					$('.shoppingitem-fig').html(json['count_products']);
					$('#cart').load('index.php?path=common/cart/info');

					$('#action_' + json['product_store_id'] + ' .add-cart-btn').parent().parent().find('.info').css('display', 'none');
					$('#action_' + json['product_store_id'] + ' .add-cart-btn').parent().parent().find('.middle-quantity').html('1')

				}

				$.ajax({
					url: 'index.php?path=common/home/cartDetails',
					type: 'post',
					dataType: 'json',
					success: function (json) {
						console.log(json);

						for (var key in json['store_note']) {
							//alert("User " + data[key] + " is #" + key); // "User john is #234"
							$('.store_note' + key).html(json['store_note'][key]);

							console.log(json['store_note'][key]);
						}

						if (json['status']) {
							console.log("yes");
							$("#proceed_to_checkout").removeAttr("disabled");
							$("#proceed_to_checkout").attr("href", json['href']);
							//$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
							//$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

							$("#proceed_to_checkout_button").css({ 'background-color': '', 'border-color': '' });
							$('.checkout-loader').hide();
							$('.checkout-modal-text').html(json['text_proceed_to_checkout']);
						} else {
							console.log("no frm jsx");
							$("#proceed_to_checkout").attr("disabled", "disabled");
							$("#proceed_to_checkout").removeAttr("href");
							//$("#proceed_to_checkout_button").html(json['amount']);
							//$('.checkout-modal-text').html(json['amount']);
							$("#proceed_to_checkout_button").css('background-color', '#ccc');
							$("#proceed_to_checkout_button").css('border-color', '#ccc');



							$('.checkout-loader').hide();
							$('.checkout-modal-text').html(json['text_proceed_to_checkout']);
						}


					}
				});

			}
		});


	},
	'update_product_type': function (key, value) {
		console.log("update product_type");
		$.ajax({
			url: 'index.php?path=checkout/cart/updateProductType',
			type: 'post',
			data: 'key=' + key + '&product_type=' + value,
			dataType: 'json',
			beforeSend: function () {
			},
			complete: function () {
			},
			success: function (json) {
				console.log("update product_type end");
				console.log(json);
				console.log("update product_type");
			}
		});
	},
        
}