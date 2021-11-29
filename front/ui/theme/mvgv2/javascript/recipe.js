$(document).delegate('.instacart-list-product-toggle', 'click', function(){
    $('.instacart-list-product').toggleClass('instacart-list-products-children-active');
});
    
//recipe page product handling 
$(document).delegate('.instacart-list-product-add', 'click', function(){
    
    $product_id = $(this).parents('.instacart-list-product').attr('data-product-id');
    $key = $(this).attr('data-key');
    
    //update item 
    if($key){
        //$qty = parseInt($(this).parents('.instacart-list-product').find('.incart').html()) + 1;    
        $qty = parseInt($('#incrt-'+$product_id).html()) + 1;    
        if($qty > 0){
            recipe_cart.update($(this).attr('data-key'),$qty);
        }
        
    //else add item     
    }else{
        recipe_cart.add($product_id, 1);        
    }
});

$(document).delegate('.modal-dialog', 'click', function(event){
    event.stopPropagation();
});

$(document).delegate('#modal', 'click', function(){
    $('#modal').modal('toggle'); 
});

$(document).delegate('.instacart-list-product-remove-from-cart', 'click', function(){
    
    $product_id = $(this).parents('.instacart-list-product').attr('data-product-id');
    // alert($product_id);
    $key = $(this).attr('data-key');

    $qty = parseInt($('#incrt-'+$product_id).html()) - 1;    
    //$qty = parseInt($(this).parents('.instacart-list-product').find('.incart').html()) - 1;    
    //alert($qty);
    if($qty > 0){

        recipe_cart.update($(this).attr('data-key'),$qty);
    }else{
        recipe_cart.remove($(this).attr('data-key'),$qty);
    }
});

// Cart add remove functions
var recipe_cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?path=checkout/cart/add',
			type: 'post',
			data: 'variation_id=0&product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				//$('#cart > button').button('loading');
			},
			complete: function() {
				//$('#cart > button').button('reset');
			},			
			success: function(json) {

				if (json['redirect']) {
                    alert('error!');
					//location = json['redirect'];
				}

				if (json['success']) {
                                        
					$('#cart').load('index.php?path=common/cart/newInfo');
					//assign key 
                        $wrapper = $('.instacart-list-product[data-product-id="'+json['product_store_id']+'"]');
                                                                
                        $wrapper.find('.instacart-list-product-remove-from-cart').css('display','inline-block');
                        $wrapper.find('.instacart-list-product-remove-from-cart').attr('data-key', json['key']);
                        $wrapper.find('.instacart-list-product-add').attr('data-key', json['key']);
                        $wrapper.find('.incart').css('display','block');
                        $wrapper.find('.incart').html(1);	

				}
			}
		});
	},
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
                $('#cart').load('index.php?path=common/cart/newInfo');

                $wrapper = $('.instacart-list-product[data-product-id="'+json['product_store_id']+'"]');
                if(quantity === 0){
                    $wrapper.find('.instacart-list-product-remove-from-cart').css('display','none');
                    
                    $wrapper.find('.instacart-list-product-add').removeAttr('data-key');

                    $wrapper.find('.incart').css('display','none');
                }else{
                    $wrapper.find('.incart').html(quantity);
                }
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
			//	$('#cart > button').button('loading');
			},
			complete: function() {
				// $('#cart > button').button('reset');
			},			
			success: function(json) {	
                $('#cart').load('index.php?path=common/cart/newInfo');
                $wrapper = $('.instacart-list-product[data-product-id="'+json['product_store_id']+'"]');
                $wrapper.find('.instacart-list-product-remove-from-cart').css('display','none');
                $wrapper.find('.instacart-list-product-add').removeAttr('data-key');
                $wrapper.find('.incart').css('display','none');
			}
		});
	}
};

