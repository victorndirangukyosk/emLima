
// Cart add remove functions
var checkout_cart = {
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?path=checkout/cart/update',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
			},			
			success: function(json) {

                //reflact changes in list 
                $('#row_'+json['product_id']+' .num').html(json['quantity']);
                       
                //update total 
                $('.cart-info-table tbody').load('index.php?path=checkout/cart/total');
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?path=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
			},			
			success: function(json) {	
                            //refresh page 
                            location = location;
			}
		});
	}
}