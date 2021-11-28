$(function(){
    
    $(document).delegate('.mob-cat-list .fa-plus','click', function(e){
       $(this).attr('class','fa fa-minus'); 
       $(this).parent().next().css('display','block');
       e.preventDefault();
    });
    
    $(document).delegate('.mob-cat-list .fa-minus','click', function(e){
       $(this).attr('class','fa fa-plus'); 
       $(this).parent().next().css('display','none');
       e.preventDefault();
    });
    
    $('.respBtn').click(function(){
        $('.mob-menu-container .respcontainer').css('display','block');
        $('.mob-menu-container .overlay').css('display','block');
    });
    
    $('.resp-close').click(function(){
        $('.mob-menu-container .respcontainer').css('display','none');
        $('.mob-menu-container .overlay').css('display','none');
    });    
});


//delivery time 
$(document).delegate('.today', 'click', function(){   
   $('select[name="delivery_timeslot['+$(this).attr('data-store-id')+']"] option[data-disable="1"]').attr('disabled','disabled'); 
    $('select[name="delivery_timeslot['+$(this).attr('data-store-id')+']"]').val(''); 
});

$(document).delegate('.tomorrow', 'click', function(){
  $('select[name="delivery_timeslot['+$(this).attr('data-store-id')+']"] option').removeAttr('disabled'); 
});

//cart module 
$(document).delegate('#cart .quantity-controller .plus', 'click', function(){
    $qty = parseInt($(this).parent().find('.num').html()) + 1;    
    if($qty > 0){
        cart.update($(this).attr('data-key'),$qty);
    }
});

$(document).delegate('#cart .quantity-controller .minus', 'click', function(){
    $qty = parseInt($(this).parent().find('.num').html()) - 1;    
    if($qty > 0){
        cart.update($(this).attr('data-key'),$qty);
    }
});


//checkout cart 
$(document).delegate('.checkout-cart .plus', 'click', function(){
    $qty = parseInt($(this).parent().find('.num').html()) + 1;    
    if($qty > 0){
        $(this).parents('tr').find('input').val($qty);
        $('#cart_form').submit(); 
    }
});

$(document).delegate('.checkout-cart .quantity-controller .minus', 'click', function(){
    $qty = parseInt($(this).parent().find('.num').html()) - 1;    
    if($qty > 0){
        $(this).parents('tr').find('input').val($qty);
        $('#cart_form').submit();
    }
});

$(document).delegate('.minus-quantity', 'click', function() {
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $qty = parseInt($qty_wrapper.html())-1;
    if ($qty > 0) {
        $qty_wrapper.html($qty);

        //update cart if exist in cart 
        if($(this).attr('data-key').length > 0){
            cart.update($(this).attr('data-key'),$qty);
        }
    }
});

$(document).delegate('.plus-quantity', 'click', function() {
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $qty = parseInt($qty_wrapper.html()) + 1;
    $qty_wrapper.html($qty);
    
    //update cart if exist in cart 
    if($(this).attr('data-key').length > 0){
        cart.update($(this).attr('data-key'),$qty);
    }
});

$(document).delegate('.add-cart-btn', 'click', function() {
    $product_id = $(this).attr('data-id');    
    $variation_id = $(this).attr('data-variation-id');
    
    if($variation_id.length === 0){
        $variation_id = 0;
    }
        
    $quantity = parseInt($(this).parent().parent().find('.middle-quantity').html());
    if ($quantity > 0) {
        cart.add($product_id, $quantity, $variation_id);
        $(this).parent().parent().find('.info').css('display','block');
        $(this).css('display','none');        
    }
});
