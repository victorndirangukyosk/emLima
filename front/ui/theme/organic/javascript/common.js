$(function(){
    
    $("form").bind("keypress", function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
    
    $('input[name=\'product_name\']').on('keydown', function(e) {
    console.log("fgr");
        if (e.keyCode == 13) {
            $('#product-search-form').submit();
        }
    });
    
    var divs = $('.mydivsss>div');
    var now = 0; // currently shown div
    divs.hide().first().show();
    /*$("button[name=next]").click(function(e) {
        divs.eq(now).hide();
        now = (now + 1 < divs.length) ? now + 1 : 0;
        divs.eq(now).show(); // show next
    });
    $("button[name=prev]").click(function(e) {
        divs.eq(now).hide();
        now = (now > 0) ? now - 1 : divs.length - 1;
        divs.eq(now).show(); // or .css('display','block');
        //console.log(divs.length, now);
    });*/
   

    /*$('#signupModal-popup').on('load', function() {

        $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
        
    })*/

    $('#login-form').on('keypress', function(e) {

        console.log("er");
        return e.which !== 13;
    });

    $('#sign-up-form').on('keypress', function(e) {
        return e.which !== 13;
    });


    $('#contactusModal').on('hidden.bs.modal', function () {
        console.log("empty form works");

        $('#contactus-message').html('');
        $('#contactus-success-message').html('');

        $(this).find('form').trigger('reset');
    });

    $('#addressModal').on('hidden.bs.modal', function () {
        console.log("empty form works");

        $('#edit-address-message').html('');
        $('#edit-address-success-message').html('');

        $('#address-message').html('');
        $('#address-success-message').html('');

        $(this).find('form').trigger('reset');
    });

    $('#signupModal-popup').on('hidden.bs.modal', function () {
        console.log("empty form works");
        $('#signup-message').html('');
        $(this).find('form').trigger('reset');
    });

    $('#forgetModal').on('hidden.bs.modal', function () {
        console.log("empty form works");

        $('#forget-message').html('');
        $('#forget-success-message').html("");

        $(this).find('form').trigger('reset');
    });

    $('#phoneModal').on('hidden.bs.modal', function () {
        console.log("phoneModal empty form works");

        $('#login-message').html('');
        $('#login-success-message').html("");

        $(this).find('form').trigger('reset');

        var divs = $('.mydivsss>div');
        var now = 0; // currently shown div
        divs.hide().first().show();
        // $("button[name=next]").click(function(e) {
        //     divs.eq(now).hide();
        //     now = (now + 1 < divs.length) ? now + 1 : 0;
        //     divs.eq(now).show(); // show next
        // });
        // $("button[name=prev]").click(function(e) {
        //     divs.eq(now).hide();
        //     now = (now > 0) ? now - 1 : divs.length - 1;
        //     divs.eq(now).show(); // or .css('display','block');
        //     //console.log(divs.length, now);
        // });

    });
    

    
    


    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {

        console.log("success");
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            document.getElementById("goToTop").style.display = "block";
        } else {
            document.getElementById("goToTop").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    
    $('#goToTop').click(function(){
        document.body.scrollTop = 0; // For Chrome, Safari and Opera 
        document.documentElement.scrollTop = 0; // For IE and Firefox
    });

    $(".add-list-png").hover(function() {

        console.log("add-list-png hover");
        $(this).css("opacity", 1);
        }, function(){
            console.log("add-list-png hover else");
        $(this).css("opacity",0.6);
    });
    
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





$(document).delegate('#add-btn', 'click', function() {
    console.log("add inc dec selectss");
    $product_id = $(this).attr('data-id');    
    $variation_id = $(this).attr('data-variation-id');
    
    //below hides 2 buttons one in popup and other in product list page

    //$('#add-btn[data-id="'+$product_id+'"]').removeAttr('display');
    $('#add-btn[data-id="'+$product_id+'"]').css({ 'display': "none" });

    $('.atc_'+$product_id).toggleClass('AtcButton__with_text___4C5OY AtcButton__with_counter___3YxLq');


   /* $(document).find('.unique_minus_button'+$product_store_id+'-'+$variation_id).css('display','none');
            $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).css('display','none');
            $(document).find('.unique_plus_button'+$product_store_id+'-'+$variation_id).css('display','none');

            $(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).removeAttr('style'); */

    if($variation_id.length === 0){
        $variation_id = 0;
    }

    
    $('.unique_minus_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_middle_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_plus_button'+$product_id+'-'+$variation_id).css('display','flex');
        
    $quantity = parseInt($(this).parent().parent().find('.middle-quantity').html());
    if ($quantity > 0) {

        cart.add($product_id, $quantity, $variation_id);
        $(this).parent().parent().find('.info').css('display','block');
        //$(this).parent().parent().find('.inc-dec-quantity').css('display','block');
        $('.unique_minus_button'+$product_id+'-'+$variation_id).css('display','flex');
        $('.unique_middle_button'+$product_id+'-'+$variation_id).css('display','flex');
        $('.unique_plus_button'+$product_id+'-'+$variation_id).css('display','flex');

        $('.unique_plus_button'+$product_id+'-'+$variation_id).css('text-transform','capitalize');

        console.log($(this).parent().parent());
        console.log("inc dec select");
    }
});

$(document).delegate('.plus-quantity', 'click', function() {
    console.log("add without cart addsedxx");
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $qty = parseInt($qty_wrapper.html()) + 1;
    
    $product_store_id = $(this).data('id');
    $variation_id = 0;

    $this = $(this);
    $product_id = $this.attr('data-id');

    $product_minimum = $this.attr('data-minimum');

    

    if ($product_minimum >= $qty  ) {

        if($this.attr('data-key').length > 0) {
            var d = cart.update($this.attr('data-key'),$qty);
           // $qty_wrapper.html($qty);
            $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
            $qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
            $qty_wrapper.html($qty);
            $('#action_'+$product_id+' .error-msg').html('');
        } 

        //$this.removeClass("tooltip");
        $this.removeAttr("data-tooltip");
        
    } else if($product_minimum == $qty - 1) {

        //$(document).tooltip(); 
        //$this.attr("data-tooltip","Limited quantity available. You can't add more than "+$product_minimum+" of this item");
        $this.attr("data-tooltip","Maximum quantity per order for this product reached");

        console.log("max qty raecahed");
        $this.attr("data-tooltip").toLowerCase();

    }

    

    /*$.ajax({
        url : 'index.php?path=checkout/cart/hasStock',
        method: 'post',
        data: {key : $this.attr('data-key'),quantity :$qty},
        success:function(data){
            if(data.stock) {
               $qty_wrapper.html($qty);
               if($this.attr('data-key').length > 0){
                    var d = cart.update($this.attr('data-key'),$qty);
                    $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
                    $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
                    $('#action_'+$product_id+' .error-msg').html('');
                } 
            }else{
                  $('#action_'+$product_id+' .info').css('display','none');
                $('#action_'+$product_id+' .error-msg').html(data.error);
                
            }
        }
    });*/


    //update cart if exist in cart 
});
/* mini cart plus*/
$(document).delegate('.mini-plus-quantity', 'click', function() {
    console.log("add without cart mini");
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $qty = parseInt($qty_wrapper.html()) + 1;

    console.log($qty_wrapper);

    $product_store_id = $(this).data('id');
    $variation_id = 0;

    $this = $(this);
    $product_id = $this.attr('data-id');

    //$qty_wrapper.html($qty);

    $product_minimum = $this.attr('data-minimum');

    if ($product_minimum >= $qty  ) {

        
        var d = cart.update($this.attr('data-key'),$qty);

        $('#action_'+$product_id+' .error-msg').html('');

        console.log("mini click");
        $('.cart-panel-content').load('index.php?path=common/cart/newInfo',function () {
            

        });

        //$this.removeClass("tooltip");
        $this.removeAttr("data-tooltip");
        
    } else if($product_minimum == $qty - 1) {

        //$(document).tooltip(); 
        //$this.attr("data-tooltip","Limited quantity available. You can't add more than "+$product_minimum+" of this item");

        $this.attr("data-tooltip","Maximum quantity per order for this product reached");


        

        $this.attr("data-tooltip").toLowerCase();

        console.log("max qty raecahedxz");
    }

    
    
    //console.log(d);
    
    
    //$('#cart').load('index.php?path=common/cart/info');
});
/* mini cart plus end*/
//cart module 
$(document).delegate('#cart .quantity-controller .plus', 'click', function(){

    
    $qty = parseInt($(this).parent().find('.num').html()) + 1; 

    $product_store_id = $(this).data('id');
    $variation_id = $(this).data('variation-id');
    // alert($product_store_id);
    $this = $(this);
    
    
    if($this.attr('data-key').length > 0){
        cart.update($this.attr('data-key'),$qty);
        $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
        $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
        console.log("mini cart controller click");
        $('#cart_'+$product_store_id+' .error-msg').html("");
        $('#action_'+$product_store_id+' .error-msg').html("");
    }

    /*$.ajax({
        url : 'index.php?path=checkout/cart/hasStock',
        method: 'post',
        data: {key : $this.attr('data-key'),quantity :$qty},
        success:function(data){
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
    });*/
 
});

$(document).delegate('.minus-quantity', 'click', function() {

    
    $qty_wrapper = $(this).parent().find('.middle-quantity');

    $product_store_id = $(this).data('id');
    $variation_id = 0;


    console.log("clickv"+$product_store_id+"s"+$variation_id);
    $qty = parseInt($qty_wrapper.html())-1;
    $product_id = $(this).attr('data-id');    
    $('#action_'+$product_id+' .error-msg').html('');
    $this = $(this);

    $this.parent().children('.plus-quantity').removeAttr("data-tooltip");

    if ($qty > 0) {
        //update cart if exist in cart 

        if($this.attr('data-key').length > 0){
            cart.update($this.attr('data-key'),$qty);
            $qty_wrapper.html($qty);
            $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
            $qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
            $qty_wrapper.html($qty);
            $('#action_'+$product_id+' .info').css('display','block');
            $('#action_'+$product_id+' .error-msg').html('');
        } 

    } else {
        if($this.attr('data-key').length > 0) {
           /* $(document).find('.unique'+$product_store_id+'-'+$variation_id).css('display','none'); */
            $(document).find('.unique_minus_button'+$product_store_id+'-'+$variation_id).css('display','none');
            $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).css('display','none');
            $(document).find('.unique_plus_button'+$product_store_id+'-'+$variation_id).css('display','none');

            $(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).removeAttr('style');
            $('.atc_'+$product_id).toggleClass('AtcButton__with_counter___3YxLq AtcButton__with_text___4C5OY ');
            
            console.log('.unique_add_button'+$product_store_id+'-'+$variation_id);
            console.log('cool grid');
            
            cart.remove($this.attr('data-key'));          
            $('#action_remove_'+$product_store_id).remove();
            
        } 
    }
});

/* mini minus cart start*/
$(document).delegate('.mini-minus-quantity', 'click', function() {

    var text = $('.checkout-modal-text').html();
    $('.checkout-modal-text').html('');
    $('.checkout-loader').show();

    $qty_wrapper = $(this).parent().find('.middle-quantity');

    $product_store_id = $(this).data('id');
    $variation_id = 0;
    console.log('minus mini cart');
    $qty = parseInt($qty_wrapper.html())-1;
    $product_id = $(this).attr('data-id');    
    $('#action_'+$product_id+' .error-msg').html('');
    $this = $(this);
    if ($qty > 0) {
        //update cart if exist in cart 
        /*$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
        $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);*/
        if($this.attr('data-key').length > 0){
            cart.update($this.attr('data-key'),$qty);
            $('#action_'+$product_id+' .info').css('display','block');
            $('#action_'+$product_id+' .error-msg').html('');
        } 

        console.log("mini click");
        $('.cart-panel-content').load('index.php?path=common/cart/newInfo',function () {
            
            console.log("mini cart click");

            $.ajax({
                url: 'index.php?path=common/home/cartDetails',
                type: 'post',
                dataType: 'json',
                success: function(json) {
                    console.log("mmon/home/cartDetails");

                    console.log(json);

                    for (var key in json['store_note']) {
                        //alert("User " + data[key] + " is #" + key); // "User john is #234"
                        $('.store_note'+key).html(json['store_note'][key]);

                        console.log(json['store_note'][key]);
                    }

                    if (json['status']) {
                        console.log("yes");
                        $("#proceed_to_checkout").removeAttr("disabled");
                        $("#proceed_to_checkout").attr("href", json['href']);
                        
                        //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);

                        //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

                        $("#proceed_to_checkout_button").css({ 'background-color' : '', 'border-color' : '' });

                        $('.checkout-loader').hide();
                        $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                    } else {    
                        console.log("no");
                        $("#proceed_to_checkout").attr("disabled", "disabled");
                        $("#proceed_to_checkout").removeAttr("href");
                        //$("#proceed_to_checkout_button").html(json['amount']);
                       // $('.checkout-modal-text').html(json['amount']);
                        $('.checkout-loader').hide();

                        $("#proceed_to_checkout_button").css('background-color', '#ccc');
                        $("#proceed_to_checkout_button").css('border-color', '#ccc');

                        $('.checkout-modal-text').html(json['text_proceed_to_checkout']);
                    }
                }
            });
        });
        
        /*$.ajax({
            url : 'index.php?path=checkout/cart/hasStock',
            method: 'post',
            data: {key : $this.attr('data-key'),quantity :$qty},
            success:function(data){
                //console.log(data);
                if(data.stock){
                    $qty_wrapper.html($qty);
                    $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
                    $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
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
        });*/
    } else {
        if($this.attr('data-key').length > 0) {
            //$(document).find('.unique'+$product_store_id+'-'+$variation_id).css('display','none');
            //$(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).removeAttr('style');
            //$(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).css('display','true');

            $(document).find('.unique_minus_button'+$product_store_id+'-'+$variation_id).css('display','none');
            $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).css('display','none');
            $(document).find('.unique_plus_button'+$product_store_id+'-'+$variation_id).css('display','none');

            $(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).removeAttr('style');
            $('.atc_'+$product_id).toggleClass('AtcButton__with_counter___3YxLq AtcButton__with_text___4C5OY ');

            console.log('.unique_add_button'+$product_store_id+'-'+$variation_id);
            console.log('cool');
            
            cart.remove($this.attr('data-key'));              
            $('#action_remove_'+$product_store_id).remove();
            
        } 
    }

    

});
/* mini cart end*/
$(document).delegate('#cart .quantity-controller .minus', 'click', function(){

    console.log("minus quantity-controller");
    $qty = parseInt($(this).parent().find('.num').html()) - 1;   
    $product_store_id = $(this).data('id');
    $variation_id = $(this).data('variation-id');
    $('#action_'+$product_store_id+' .error-msg').html('');
    $qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    $this = $(this);
    if($qty > 0){

        $qty_wrapper.html($qty);
        if($this.attr('data-key').length > 0){
            cart.update($this.attr('data-key'),$qty);
            $('#action_'+$product_store_id+' .info').css('display','block');
        } 
        /*$.ajax({
            url : 'index.php?path=checkout/cart/hasStock',
            method: 'post',
            data: {key : $this.attr('data-key'),quantity :$qty},
            success:function(data){
                if(data.stock){
                    $qty_wrapper.html($qty);
                    if($this.attr('data-key').length > 0){
                        cart.update($this.attr('data-key'),$qty);
                        $('#action_'+$product_store_id+' .info').css('display','block');
                    } 
                }else{
                    console.log("ssssss");
                    $('#action_'+$product_store_id+' .error-msg').html('');
                }
            }
        });*/
    }
});


$(document).on("click", ".add-to-list", function () {
    console.log("add to list js");
        //$('#listModal').modal('show');
     var myProductId = $(this).data('id');
     $(".listproductId").val(myProductId);
});
$(document).on("click", "#list-create-button", function () {
    console.log("list-create-button js");
     //$('#list-create-form').submit();
     $('#list-message-error').html('');
     $('#list-message-success').html('');
    if($('#list-name').val().length > 0 ) {
        var text = $('.list-create-modal-text').html();
        $('.list-create-modal-text').html('');
        $('.list-create-loader').show();
        console.log($('#list-create-form').serialize());
        //listproductId=7&name=2
        $.ajax({
            url: 'index.php?path=account/wishlist/createWishlist',
            type: 'post',
            data: $('#list-create-form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);

                

                if (json['status']) {

                    $('#list-message-success').html(json['message']);
                    
                    console.log("erg sage-suc");
                    data = {
                        product_id : $(".listproductId").val()
                    }

                    $.ajax({
                        url: 'index.php?path=account/wishlist/getProductWislists',
                        type: 'post',
                        data:data,
                        dataType: 'json',
                        success: function(json) {
                            if (json['status']) {
                                $('#users-list').html(json['html']);

                                $('.list-create-modal-text').html(text);
                                $('.list-create-loader').hide();
                            }
                        }
                    });

                    //setTimeout(function(){ $('#listModal').modal('hide'); window.location.reload(false);},1500);
                    
                } else {
                    $('#list-message-error').html(json['message']);
                    
                }
                //$("input:checkbox[name='add_to_list[]']").prop('checked', false);
            }
        });
    }
    
});




$(document).on("click", "#add-in-list-button", function () {
    console.log("add-in-list-button jsxxx123 new");
     //$('#list-create-form').submit();
    $('#list-message-error').html('');
    $('#list-message-success').html('');
    console.log($('#add-in-list').serialize());

    if(true) {

        console.log("if");

        var text = $('.add-in-list-modal-text').html();
        $('.add-in-list-modal-text').html('');
        $('.add-in-list-loader').show();
        
        console.log("why");
        //listproductId=7&name=2
        $.ajax({
            url: 'index.php?path=account/wishlist/addProductToWishlist',
            type: 'post',
            data: $('#add-in-list').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);

                
                $('.add-in-list-modal-text').html(text);
                $('.add-in-list-loader').hide();
                if (json['status']) {

                    $('#list-message-success').html(json['message']);
                    
                     //window.location.reload(false);
                    setTimeout(function(){ $('#list-message-success').html('');$('#list-message-error').html('');$('#listModal').modal('hide');},1500);
                    
                } else {
                   $('#list-message-error').html(json['message']);
                    
                }
            }
        });
    } else {

        console.log("else");
        //$('#list-message-error').html('');
    }
});

function uncheckAll() {
    console.log('uncheckAll');
}

/* Newly adding*/


$(document).delegate('#forget-button', 'click', function() {
    console.log("forget-form clickxx");
    
    var text = $('.forget-modal-text').html();
    $('.forget-modal-text').html('');
    $('.forget-loader').show();

    $.ajax({
        url: 'index.php?path=account/forgotten',
        type: 'post',
        data: $('#forget-form').serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            console.log(json);

            

            if (json['status']) {

                $('#forgetModal').find('form').trigger('reset');
                
                $('#forget-message').html('');
                //$('#forget-success-message').html(json['text_message']);
                $('#forget-success-message').html("<p style='color:green'>"+json['text_message']+"</p>");
                
                $('.forget-modal-text').html(text);
                $('.forget-loader').hide();

                //setTimeout(function(){ window.location.reload(false); }, 1000);
            } else {
                $error = '';

                $('.forget-modal-text').html(text);
                $('.forget-loader').hide();

                console.log("forget-form clickxxrgre");
                if(json['text_message']){
                    $error += json['text_message'];
                }
                //$('#forget-message').html($error);
                console.log($error);
                $('#forget-message').html("<p style='color:red'>"+$error+"</p>");
                
            }
        }
    });
});
$(document).delegate('#facebook-signup', 'click', function(e) {
    console.log("facebook-btn click");
    $.ajax({
        url: 'index.php?path=common/home/getFacebookRedirectUrl&redirect_url=<?= $_SERVER["REQUEST_URI"]?>',
        type: 'post',
        dataType: 'json',

        success: function(json) {
            console.log(json);
            e.preventDefault();
            location.href = json['facebook'];
        }
    });
});

$(document).delegate('#facebook-login', 'click', function(e) {
    console.log("facebook-btn click");
    $.ajax({
        url: 'index.php?path=common/home/getFacebookRedirectUrl&redirect_url=<?= $_SERVER["REQUEST_URI"]?>',
        type: 'post',
        dataType: 'json',
        success: function(json) {
            console.log(json);
            e.preventDefault();
            location.href = json['facebook'];
        }
    });
});

$(document).delegate('#login', 'click', function() {
    console.log("log click");
    var text = $('.login-modal-text').html();
    $('.login-modal-text').html('');
    $('.login-loader').show();

    $.ajax({
        url: 'index.php?path=account/login',
        type: 'post',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function(json) {
            console.log(json);
            if (json['status']) {
                window.location.reload(false);    
            } else {
                $('.login-modal-text').html(text);
                $('.login-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#login-message').html("<p style='color:red'>"+json['error_warning']+"</p>");
            }
        }
    });
});


$(document).delegate('#resendVerificationMail', 'click', function() {
    console.log("resendVerificationMail click");
    /*var text = $('.login-modal-text').html();
    $('.login-modal-text').html('');
    $('.login-loader').show();*/

    $.ajax({
        url: 'index.php?path=account/activate/resendEmail',
        type: 'post',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function(json) {
            console.log(json);
            if (json['status']) {
                $('#login-message').html("<p style='color:green'>"+json['success_message']+"</p>");
            } else {
                /*$('.login-modal-text').html(text);
                $('.login-loader').hide();*/
                $('#login-message').html("<p style='color:red'>"+json['error_warning']+"</p>");
            }
        }
    });
});

$(document).delegate('#contactus', 'click', function() {
    console.log("contactus click");

    var text = $('.contact-modal-text').html();
    $('.contact-modal-text').html('');
    $('.contact-loader').show();


    $.ajax({
        url: 'index.php?path=information/contact',
        type: 'post',
        data: $('#contactus-form').serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            console.log(json);
            if (json['status']) {
                $('#contactus-message').html('');
                $('#contactus-success-message').html(json['text_message']);

                $('.contact-modal-text').html(text);
                $('.contact-loader').hide();

                
                $('#contactusModal').find('form').trigger('reset');
                
            } else {
                $error = '';

                if(json['error_email']){
                    $error += json['error_email']+'<br/>';
                }
                if(json['error_enquiry']){
                    $error += json['error_enquiry']+'<br/>';
                }
                if(json['error_name']){
                    $error += json['error_lastname']+'<br/>';
                }

                $('.contact-modal-text').html(text);
                $('.contact-loader').hide();

                $('#contactus-message').html("<p style='color:red'>"+$error+"</p>");
            }
        }
    });
});

function ValidateEmail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm.emailAddr.value))
  {
    return (true)
  }
    alert("You have entered an invalid email address!")
    return (false)
}

$(document).delegate('#login_send_otp', 'click', function() {
    console.log("login_send_otp click");
    

    
    if(!($('#login-form #phone_number').val().length == 0 &&  $('#login-form #email').val().length == 0)) {

        var text = $('.login-modal-text').html();
        $('.login-modal-text').html('');
        $('.login-loader').show();

        $.ajax({
            url: 'index.php?path=account/login/login_send_otp',
            type: 'post',
            data: $('#login-form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json['status']) {
                    
                    $('.login-modal-text').html(text);
                    $('.login-loader').hide();
                    //$('#login-message').html(json['error_warning']);
                    $('#otp-message').html("<p style='color:green'>"+json['success_message']+"</p>");

                    $('#customer_id').val(json['customer_id']);
                    
                    var divs = $('.mydivsss>div');
                    var now = 0; // currently shown div
                    
                    divs.eq(now).hide();
                    now = (now + 1 < divs.length) ? now + 1 : 0;
                    divs.eq(now).show(); // show otp next
                

                } else {
                    $('.login-modal-text').html(text);
                    $('.login-loader').hide();
                    //$('#login-message').html(json['error_warning']);
                    $('#login-message').html("<p style='color:red'>"+json['error_warning']+"</p>");
                }
            }
        });    
    } else {
        $('#login-form #phone_number').focus();
    }
    
});

$(document).delegate('#login_resend_otp', 'click', function() {
    console.log("login_resend_otp click");
    var text = $('.login-modal-text').html();
    $('.login-modal-text').html('');
    $('.login-loader').show();

    
        
    $.ajax({
        url: 'index.php?path=account/login/login_send_otp',
        type: 'post',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function(json) {
            console.log(json);
            if (json['status']) {
                
                $('.login-modal-text').html(text);
                $('.login-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#otp-message').html("<p style='color:green'>"+json['success_message']+"</p>");

                $('#customer_id').val(json['customer_id']);
                
                var divs = $('.mydivsss>div');
                var now = 0; // currently shown div
                
                divs.eq(now).hide();
                now = (now + 1 < divs.length) ? now + 1 : 0;
                divs.eq(now).show(); // show otp next
            

            } else {
                $('.login-modal-text').html(text);
                $('.login-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#login-message').html("<p style='color:red'>"+json['error_warning']+"</p>");
            }
        }
    });
});



$(document).delegate('#login_verify_otp', 'click', function() {
    console.log("login_verify_otp click");
    var text = $('.login-otp-modal-text').html();
    $('.login-otp-modal-text').html('');
    $('.login-otp-loader').show();
    
    $.ajax({
        url: 'index.php?path=account/login/login_verify_otp',
        type: 'post',
        data: $('#login-otp-form').serialize(),
        dataType: 'json',
        success: function(json) {
            console.log(json);
            
            if (json['status']) {
                window.location.reload(false);    
            } else {
                $('.login-otp-modal-text').html(text);
                $('.login-otp-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#otp-message').html("<p style='color:red'>"+json['error_warning']+"</p>");
            }
        }
    });
});

$(document).delegate('#signup', 'click', function() {

    console.log($('#register_verify_otp').val());
    console.log($('input[name="agree_checkbox"]:checked').length);

    if($('input[name="agree_checkbox"]:checked').length)
    {
        $('#error_agree').hide();
    
        var text = $('.signup-modal-text').html();
        $('.signup-modal-text').html('');
        $('.signup-loader').show();

        $('#signup-message').html('please wait...');

        console.log($('#register_verify_otp').val());

        if($('#register_verify_otp').val() == 'yes') {
            var url = 'index.php?path=account/register/register_verify_otp';
        } else {
            var url = 'index.php?path=account/register/register_send_otp';
        }

        $.ajax({
            url: url,
            type: 'post',
            data: $('#sign-up-form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                console.log('signup return');


                if (json['status']) {

                    
                    $('#signup-message').html('<p style="color:green"> '+ json['success_message']+'</p>');
                    
                   

                    if($('#register_verify_otp').val() == 'yes') {

                        $('.signup-modal-text').html(text);

                        window.location.reload(false);
                        
                    } else {
                        $('.signup_otp_div').show();
                        $('#other_signup_div').hide();
                        // button text to read verify otp
                        $('.signup-modal-text').html(json['text_verify_otp']);

                    }

                    $('.signup-loader').hide();

                    $('#register_verify_otp').val('yes');
                    
               
                    return false;
                } else {   

                    /*if($('#register_verify_otp').val() == 'yes') {
                        $('#register_verify_otp').val('no');     
                    }*/
                    

                    $error = '';

                    if(json['error_email']){
                        $error += json['error_email']+'<br/>';
                    }
                    if(json['error_firstname']){
                        $error += json['error_firstname']+'<br/>';
                    }
                    if(json['error_telephone_exists']){
                        $error += json['error_telephone_exists']+'<br/>';
                    }

                    if(json['error_lastname']){
                        $error += json['error_lastname']+'<br/>';
                    }
                    if(json['error_telephone']){
                        $error += json['error_telephone']+'<br/>';
                    }
                    if(json['error_dob']){
                        $error += json['error_dob']+'<br/>';
                    }
                    if(json['error_gender']){
                        $error += json['error_gender']+'<br/>';
                    }
                    if(json['error_tax']){
                        $error += json['error_tax']+'<br/>';
                    }
                    if(json['error_password']){
                        $error += json['error_password']+'<br/>';
                    }
                    if(json['error_company_name_address']){
                        $error += json['error_company_name_address']+'<br/>';
                    }

                    if(json['error_warning']){
                        $error += json['error_warning']+'<br/>';
                    }

                    

                    

                    $('.signup-modal-text').html(text);
                    $('.signup-loader').hide();
                    $('#signup-message').html("<p style='color:red'>"+$error+"</p>");
                }
            }
        });
    } else{
      // unchecked
      console.log("nucheck error");
      $('#error_agree').show();
      //$('#error_agree').html($('#error_agree_text').val());
    }
});

$(document).delegate('#signup-resend-otp', 'click', function() {

    var text = $('.signup-modal-text').html();
    $('.signup-modal-text').html('');
    $('.signup-loader').show();

    $('#signup-message').html('please wait...');

    console.log($('#register_verify_otp').val());

    var url = 'index.php?path=account/register/register_send_otp';
    
    $.ajax({
        url: url,
        type: 'post',
        data: $('#sign-up-form').serialize(),
        dataType: 'json',
        success: function(json) {
            console.log(json);
            console.log('signup return');


            if (json['status']) {

                
                $('#signup-message').html('<p style="color:green"> '+ json['success_message']+'</p>');
                
               
                $('.signup_otp_div').show();
                $('#other_signup_div').hide();
                // button text to read verify otp
                $('.signup-modal-text').html(json['text_verify_otp']);

                $('.signup-loader').hide();

                $('#register_verify_otp').val('yes');
                
           
                return false;
            } else {   

                /*if($('#register_verify_otp').val() == 'yes') {
                    $('#register_verify_otp').val('no');     
                }*/
                

                $error = '';

                if(json['error_email']){
                    $error += json['error_email']+'<br/>';
                }
                if(json['error_firstname']){
                    $error += json['error_firstname']+'<br/>';
                }
                if(json['error_telephone_exists']){
                    $error += json['error_telephone_exists']+'<br/>';
                }

                if(json['error_lastname']){
                    $error += json['error_lastname']+'<br/>';
                }
                if(json['error_telephone']){
                    $error += json['error_telephone']+'<br/>';
                }
                if(json['error_dob']){
                    $error += json['error_dob']+'<br/>';
                }
                if(json['error_gender']){
                    $error += json['error_gender']+'<br/>';
                }
                if(json['error_tax']){
                    $error += json['error_tax']+'<br/>';
                }
                if(json['error_password']){
                    $error += json['error_password']+'<br/>';
                }
                if(json['error_warning']){
                    $error += json['error_warning']+'<br/>';
                }

                $('.signup-modal-text').html(text);
                $('.signup-loader').hide();
                $('#signup-message').html("<p style='color:red'>"+$error+"</p>");
            }
        }
    });
});