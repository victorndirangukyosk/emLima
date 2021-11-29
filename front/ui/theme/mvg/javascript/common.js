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

$(document).delegate('.plus-quantity', 'click', function() {
    console.log("add without cart addedxx");
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $qty = parseInt($qty_wrapper.html()) + 1;
    
    $product_store_id = $(this).data('id');
    $variation_id = 0;

    console.log($product_store_id+"ss"+$variation_id);
    $this = $(this);
    $product_id = $this.attr('data-id');

    $.ajax({
        url : 'index.php?path=checkout/cart/hasStock',
        method: 'post',
        data: {key : $this.attr('data-key'),quantity :$qty},
        success:function(data){
            //console.log(data);
            if(data.stock){
               $qty_wrapper.html($qty);
               if($this.attr('data-key').length > 0){
                    var d = cart.update($this.attr('data-key'),$qty);
                    //$qty_wrapper.html($qty);
                    $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);

                     // $('#action_'+$product_id+' .error-msg').html();
                    //$('#action_'+$product_id+' .info').css('display','none');
                    $('#action_'+$product_id+' .error-msg').html('');
                } 
            }else{
                console.log("ss");
                  $('#action_'+$product_id+' .info').css('display','none');
                $('#action_'+$product_id+' .error-msg').html(data.error);
                
            }
        }
    });


    //update cart if exist in cart 
});
//cart module 
$(document).delegate('#cart .quantity-controller .plus', 'click', function(){

    
    $qty = parseInt($(this).parent().find('.num').html()) + 1; 

    $product_store_id = $(this).data('id');
    $variation_id = $(this).data('variation-id');
    // alert($product_store_id);
    $this = $(this);
    
    

    $.ajax({
        url : 'index.php?path=checkout/cart/hasStock',
        method: 'post',
        data: {key : $this.attr('data-key'),quantity :$qty},
        success:function(data){
            //console.log(data);
            if(data.stock){
                 $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
               if($this.attr('data-key').length > 0){
                    cart.update($this.attr('data-key'),$qty);
                    //$qty_wrapper.html($qty);
                   $('#cart_'+$product_store_id+' .error-msg').html("");
                   $('#action_'+$product_store_id+' .error-msg').html("");
                } 
            }else{
                console.log("sss"+'#cart'+$product_store_id);
                 $('#action_'+$product_store_id+' .info').css('display','none');
                $('#action_'+$product_store_id+' .error-msg').html(data.error);

                

                
            }
        }
    });
 
});

$(document).delegate('.minus-quantity', 'click', function() {

    
    $qty_wrapper = $(this).parent().find('.middle-quantity');

    $product_store_id = $(this).data('id');
    $variation_id = 0;

    console.log("click"+$product_store_id+"s"+$variation_id);
    $qty = parseInt($qty_wrapper.html())-1;
    $product_id = $(this).attr('data-id');    
    $('#action_'+$product_id+' .error-msg').html('');
    $this = $(this);
    if ($qty > 0) {
        //update cart if exist in cart 

        $.ajax({
            url : 'index.php?path=checkout/cart/hasStock',
            method: 'post',
            data: {key : $this.attr('data-key'),quantity :$qty},
            success:function(data){
                //console.log(data);
                if(data.stock){
                    $qty_wrapper.html($qty);
                    $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
                   if($this.attr('data-key').length > 0){
                        cart.update($this.attr('data-key'),$qty);
                        //$qty_wrapper.html($qty);
                        
                        $('#action_'+$product_id+' .info').css('display','block');
                        $('#action_'+$product_id+' .error-msg').html('');
                    } 
                }else{
                    console.log("ssss");
                    $('#action_'+$product_id+' .error-msg').html('');
                }
            }
        });
     

    }
});

$(document).delegate('#cart .quantity-controller .minus', 'click', function(){

    console.log("minus quantity-controller");
    $qty = parseInt($(this).parent().find('.num').html()) - 1;   
    $product_store_id = $(this).data('id');
    $variation_id = $(this).data('variation-id');
    // alert($product_store_id);
    $('#action_'+$product_store_id+' .error-msg').html('');
    $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    $this = $(this);
    if($qty > 0){
        // cart.update($(this).attr('data-key'),$qty);

         $.ajax({
            url : 'index.php?path=checkout/cart/hasStock',
            method: 'post',
            data: {key : $this.attr('data-key'),quantity :$qty},
            success:function(data){
                //console.log(data);
                if(data.stock){
                     $qty_wrapper.html($qty);
                   if($this.attr('data-key').length > 0){
                        cart.update($this.attr('data-key'),$qty);
                        //$qty_wrapper.html($qty);
                       
                        $('#action_'+$product_store_id+' .info').css('display','block');
                    } 
                }else{
                    console.log("ssssss");
                    $('#action_'+$product_store_id+' .error-msg').html('');
                }
            }
        });
    }
});

