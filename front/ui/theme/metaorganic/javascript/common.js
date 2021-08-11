$(function () {

    $("form").bind("keypress", function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    $('input[name=\'product_name\']').on('keydown', function (e) {
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

    $('#login-form').on('keypress', function (e) {

        console.log("er");
        return e.which !== 13;
    });

    $('#sign-up-form').on('keypress', function (e) {
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
    window.onscroll = function () { scrollFunction() };

    function scrollFunction() {

        console.log("success");
        var goToTops =  document.getElementById("goToTop");
        if(typeof(goToTops) != 'undefined' && goToTops != null) {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            document.getElementById("goToTop").style.display = "block";
        } else {
            document.getElementById("goToTop").style.display = "none";
        }
        }
    }

    // When the user clicks on the button, scroll to the top of the document

    $('#goToTop').click(function () {
        document.body.scrollTop = 0; // For Chrome, Safari and Opera 
        document.documentElement.scrollTop = 0; // For IE and Firefox
    });

    $(".add-list-png").hover(function () {

        console.log("add-list-png hover");
        $(this).css("opacity", 1);
    }, function () {
        console.log("add-list-png hover else");
        $(this).css("opacity", 0.6);
    });

    $(document).delegate('.mob-cat-list .fa-plus', 'click', function (e) {
        $(this).attr('class', 'fa fa-minus');
        $(this).parent().next().css('display', 'block');
        e.preventDefault();
    });

    $(document).delegate('.mob-cat-list .fa-minus', 'click', function (e) {
        $(this).attr('class', 'fa fa-plus');
        $(this).parent().next().css('display', 'none');
        e.preventDefault();
    });

    $('.respBtn').click(function () {
        $('.mob-menu-container .respcontainer').css('display', 'block');
        $('.mob-menu-container .overlay').css('display', 'block');
    });

    $('.resp-close').click(function () {
        $('.mob-menu-container .respcontainer').css('display', 'none');
        $('.mob-menu-container .overlay').css('display', 'none');
    });
});

//delivery time 
$(document).delegate('.today', 'click', function () {
    $('select[name="delivery_timeslot[' + $(this).attr('data-store-id') + ']"] option[data-disable="1"]').attr('disabled', 'disabled');
    $('select[name="delivery_timeslot[' + $(this).attr('data-store-id') + ']"]').val('');
});

$(document).delegate('.tomorrow', 'click', function () {
    $('select[name="delivery_timeslot[' + $(this).attr('data-store-id') + ']"] option').removeAttr('disabled');
});

// Products variation

$(document).delegate('.product-variation', 'change', function () {
    const newProductId = $(this).children("option:selected").val();
    const newPrice = $(this).children("option:selected").attr('data-price');
    const newSpecial = $(this).children("option:selected").attr('data-special');

    // TODO: Change trailing -0 to variations_id?
    const newQuantityInputId = 'cart-qty-' + newProductId + '-0';

    let parentDiv = $(this).closest('.setproductimg');
    let dataHolder = parentDiv.find('#add-cart-btn');
    let productQuantityInput = parentDiv.find('.input-cart-qty');
    let specialLabel = parentDiv.find('.-DeRq');
    let priceLabel = parentDiv.find('._3QV9M');

    specialLabel.html(newSpecial);
    priceLabel.html('<strike>' + newPrice + '</strike>');
    productQuantityInput.attr('id', newQuantityInputId);
    dataHolder.attr('data-id', newProductId);
});



//checkout cart 
$(document).delegate('.checkout-cart .plus', 'click', function () {
    $qty = parseInt($(this).parent().find('.num').html()) + 1;



    if ($qty > 0) {
        $(this).parents('tr').find('input').val($qty);
        $('#cart_form').submit();
    }
});

$(document).delegate('.checkout-cart .quantity-controller .minus', 'click', function () {
    $qty = parseInt($(this).parent().find('.num').html()) - 1;
    if ($qty > 0) {
        $(this).parents('tr').find('input').val($qty);
        $('#cart_form').submit();
    }
});





$(document).delegate('#add-btn', 'click', function () {

    console.log("add inc dec selectss");
    $product_id = $(this).attr('data-id');
    $variation_id = $(this).attr('data-variation-id');
    $store_id = $(this).attr('data-store-id');

    //below hides 2 buttons one in popup and other in product list page

    //$('#add-btn[data-id="'+$product_id+'"]').removeAttr('display');
    $('#add-btn[data-id="' + $product_id + '"]').css({ 'display': "none" });

    $('.atc_' + $product_id).toggleClass('AtcButton__with_text___4C5OY AtcButton__with_counter___3YxLq');


    /* $(document).find('.unique_minus_button'+$product_store_id+'-'+$variation_id).css('display','none');
             $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).css('display','none');
             $(document).find('.unique_plus_button'+$product_store_id+'-'+$variation_id).css('display','none');
 
             $(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).removeAttr('style'); */

    if ($variation_id.length === 0) {
        $variation_id = 0;
    }


    $('.unique_minus_button' + $product_id + '-' + $variation_id).css('display', 'flex');
    $('.unique_middle_button' + $product_id + '-' + $variation_id).css('display', 'flex');
    $('.unique_plus_button' + $product_id + '-' + $variation_id).css('display', 'flex');

    $quantity = parseInt($(this).parent().parent().find('.middle-quantity').html());
    if ($quantity > 0) {

        cart.add($product_id, $quantity, $variation_id, $store_id);
        $(this).parent().parent().find('.info').css('display', 'block');
        //$(this).parent().parent().find('.inc-dec-quantity').css('display','block');
        $('.unique_minus_button' + $product_id + '-' + $variation_id).css('display', 'flex');
        $('.unique_middle_button' + $product_id + '-' + $variation_id).css('display', 'flex');
        $('.unique_plus_button' + $product_id + '-' + $variation_id).css('display', 'flex');

        $('.unique_plus_button' + $product_id + '-' + $variation_id).css('text-transform', 'capitalize');

        console.log($(this).parent().parent());
        console.log("inc dec select");
    }
});

$(document).delegate('#add-cart-btn', 'click', function () {


    console.log("add inc dec selectss");
    $product_id = $(this).attr('data-id');
    $variation_id = $(this).attr('data-variation-id');
    $store_id = $(this).attr('data-store-id');
    $action = $(this).attr('data-action');
    $key = $(this).attr('data-key');
    //below hides 2 buttons one in popup and other in product list page

    //$('#add-btn[data-id="'+$product_id+'"]').removeAttr('display');
    //$('#add-btn[data-id="'+$product_id+'"]').css({ 'display': "none" });

    // $('.atc_'+$product_id).toggleClass('AtcButton__with_text___4C5OY AtcButton__with_counter___3YxLq');

    if ($variation_id.length === 0) {
        $variation_id = 0;
    }


    /*$('.unique_minus_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_middle_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_plus_button'+$product_id+'-'+$variation_id).css('display','flex');
    */
    //$quantity = parseInt($(this).parent().parent().find('.middle-quantity').html());
    $quantity = $('#cart-qty-' + $product_id + '-' + $variation_id).val();
    $quantity = $quantity;
    $quantityParsed = parseInt($quantity);
    //alert($quantity);
    //alert($action);
    // TODO: Adding multiple variants of same product to cart?
    if ($quantityParsed > 0) {
        if ($action == 'add') {
            cart.add($product_id, $quantity, $variation_id, $store_id);
            $(this).attr('data-action', 'update');
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            //$('#flag-qty-id-'+$product_id+'-'+$variation_id).html($quantity+' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html(' items added in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
        } else {
            cart.update($key, $quantity);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            // $('#flag-qty-id-'+$product_id+'-'+$variation_id).html($quantity+' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html(' items added in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
        }
    } else {
        if ($action == 'update') {
            if ($quantity == 0) {
                $(this).attr('data-action', 'add');
            }
            cart.update($key, $quantity);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#3baa33");
            // $('#flag-qty-id-'+$product_id+'-'+$variation_id).html($quantity+' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html(' items added in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "none");
        }
    }
});


$(document).delegate('#add-cart-btnnew', 'click', function () {
    $product_id = $(this).attr('data-id');
    $Searchid = $(this).attr('Searchid');

    $variation_id = $(this).attr('data-variation-id');
    $store_id = $(this).attr('data-store-id');
    $action = $(this).attr('data-action');
    $key = $(this).attr('data-key');
    $productnotes = $(this).attr('data-product_notes');
    console.log('product notes identification');
    console.log($productnotes);
    if (typeof $productnotes !== typeof undefined && $productnotes !== false) {
        $product_notes = $productnotes;
    }
    else {
        $product_notes = $('#product_notes').val();
    }
    $produce_type = $('select[name="produce-type"] option:selected').val();
    if ($(".produce-type").length) {
        $mainquantity = $('.produce-type').attr('data-defaultquantity');
        $oldquantity = $('select[name="produce-type"] option:selected').attr('datavalue');
    }
    else {
        $mainquantity = 0;
        $oldquantity = 0;
    }
    if ($Searchid == 1) {
        $quantityadded = $(this).attr('quantityadded');
        console.log($Searchid);//if focus is not set, the popup is not closing
        $('#product_name').focus();
        $('#product_name').val("");
        $mainquantity = $quantityadded;
    }

    //below hides 2 buttons one in popup and other in product list page

    //$('#add-btn[data-id="'+$product_id+'"]').removeAttr('display');
    //$('#add-btn[data-id="'+$product_id+'"]').css({ 'display': "none" });

    // $('.atc_'+$product_id).toggleClass('AtcButton__with_text___4C5OY AtcButton__with_counter___3YxLq');

    if ($variation_id.length === 0) {
        $variation_id = 0;
    }
    $ripe = $("#ripe").val();

    console.log("ripe");
    console.log($ripe);

    /*$('.unique_minus_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_middle_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_plus_button'+$product_id+'-'+$variation_id).css('display','flex');
    */
    //$quantity = parseInt($(this).parent().parent().find('.middle-quantity').html());
    $quantity = $('#cart-qty-' + $product_id + '-' + $variation_id).val();
    $quantity = parseFloat($quantity);
    $newquantityvalue = 0;
    $newquantityvalue = parseFloat($mainquantity) + $quantity - parseFloat($oldquantity);

    // TODO: Adding multiple variants of same product to cart? 
    if ($quantity > 0) {
        if ($action == 'add') {
            //cart.add($product_id, $quantity, $variation_id,$store_id,$ripe);
            cart.add($product_id, $quantity, $variation_id, $store_id, $product_notes, $produce_type);
            $(this).attr('data-action', 'update');
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
            
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").css("display", "block");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").css("display", "block");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
        } else {
            //cart.update($key,$quantity,$ripe); 
            cart.update($key, $quantity, $product_notes, $produce_type);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($quantity + ' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
            
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").css("display", "block");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").css("display", "block");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
        }
        console.log('#popup_product_' + $product_id);

        // $('#popup_product_'+$product_id).dialog('close');
        $('#popup_product_' + $product_id).modal("hide");
        //    $('#testID').dialog('close');
        $('#testID').modal('hide');
    } else {

        if ($action == 'update') {
            if ($quantity == 0) {
                $(this).attr('data-action', 'add');
            }
            cart.update($key, $quantity);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#3baa33");

            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');

            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "none");
            
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").css("display", "none");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").css("display", "none");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');

        }

        else if ($action == 'add') {
            //alert($produce_type);
            cart.update($key, $quantity, '', $produce_type);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            if ($newquantityvalue > 0) {
                $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
                
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").css("display", "block");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").css("display", "block");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            }
            else {
                $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#3baa33");
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "none");
                
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").css("display", "none");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(1)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").css("display", "none");
            $("[id=flag-qty-id-"+ $product_id +'-'+ $variation_id +"]:eq(2)").html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            }

        }
    }
});

$(document).delegate('#editorderadd-cart-btnnew', 'click', function () {
    $product_id = $(this).attr('data-id');
    $Searchid = $(this).attr('Searchid');

    $variation_id = $(this).attr('data-variation-id');
    $store_id = $(this).attr('data-store-id');
    $action = $(this).attr('data-action');
    $key = $(this).attr('data-key');
    $productnotes = $(this).attr('data-product_notes');
    $order_id = $('#edit_order_id').val();
    console.log('product notes identification');
    console.log($productnotes);
    if (typeof $productnotes !== typeof undefined && $productnotes !== false) {
        $product_notes = $productnotes;
    }
    else {
        $product_notes = $('#product_notes').val();
    }
    $produce_type = $('select[name="produce-type"] option:selected').val();
    if ($(".produce-type").length) {
        $mainquantity = $('.produce-type').attr('data-defaultquantity');
        $oldquantity = $('select[name="produce-type"] option:selected').attr('datavalue');
    }
    else {
        $mainquantity = 0;
        $oldquantity = 0;
    }
    if ($Searchid == 1) {
        $quantityadded = $(this).attr('quantityadded');
        console.log($Searchid);//if focus is not set, the popup is not closing
        $('#product_name').focus();
        $('#product_name').val("");
        $mainquantity = $quantityadded;
    }

    //below hides 2 buttons one in popup and other in product list page

    //$('#add-btn[data-id="'+$product_id+'"]').removeAttr('display');
    //$('#add-btn[data-id="'+$product_id+'"]').css({ 'display': "none" });

    // $('.atc_'+$product_id).toggleClass('AtcButton__with_text___4C5OY AtcButton__with_counter___3YxLq');

    if ($variation_id.length === 0) {
        $variation_id = 0;
    }
    $ripe = $("#ripe").val();

    console.log("ripe");
    console.log($ripe);

    /*$('.unique_minus_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_middle_button'+$product_id+'-'+$variation_id).css('display','flex');
    $('.unique_plus_button'+$product_id+'-'+$variation_id).css('display','flex');
    */
    //$quantity = parseInt($(this).parent().parent().find('.middle-quantity').html());
    $quantity = $('#cart-qty-' + $product_id + '-' + $variation_id).val();
    $quantity = parseFloat($quantity);
    $newquantityvalue = 0;
    $newquantityvalue = parseFloat($mainquantity) + $quantity - parseFloat($oldquantity);

    // TODO: Adding multiple variants of same product to cart? 
    if ($quantity > 0) {
        if ($action == 'editorderadd') {
            //cart.add($product_id, $quantity, $variation_id,$store_id,$ripe);
            cart.editorderadd($product_id, $quantity, $variation_id, $store_id, $product_notes, $produce_type, $order_id);
            $(this).attr('data-action', 'editorderupdate');
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
        } else {
            //cart.update($key,$quantity,$ripe); 
            cart.editorderupdate($key, $quantity, $product_notes, $produce_type);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($quantity + ' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
        }
        console.log('#popup_product_' + $product_id);

        // $('#popup_product_'+$product_id).dialog('close');
        $('#popup_product_' + $product_id).modal("hide");
        //    $('#testID').dialog('close');
        $('#testID').modal('hide');
    } else {

        if ($action == 'editorderupdate') {
            if ($quantity == 0) {
                $(this).attr('data-action', 'editorderadd');
            }
            cart.editorderupdate($key, $quantity);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#3baa33");

            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');

            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "none");

        }

        else if ($action == 'editorderadd') {
            //alert($produce_type);
            cart.editorderupdate($key, $quantity, '', $produce_type, $order_id);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($quantity);
            if ($newquantityvalue > 0) {
                $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
            }
            else {
                $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#3baa33");
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($newquantityvalue + ' items in cart <i class="fas fa-flag"></i>');
                $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "none");
            }

        }
    }
});


$(document).delegate('.plus-quantity', 'click', function () {
    console.log("add without cart addsedxx");
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $qty = parseInt($qty_wrapper.html()) + 1;


    $product_store_id = $(this).data('id');
    $variation_id = 0;

    $this = $(this);
    $product_id = $this.attr('data-id');

    $product_minimum = $this.attr('data-minimum');



    if ($product_minimum >= $qty) {

        if ($this.attr('data-key').length > 0) {
            var d = cart.update($this.attr('data-key'), $qty);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);

            // $qty_wrapper.html($qty);
            $qty_wrapper = $(document).find('#' + $product_store_id + '-' + $variation_id + ' .middle-quantity').html($qty);
            $qty_wrapper = $(document).find('.unique_middle_button' + $product_store_id + '-' + $variation_id).html($qty);
            $qty_wrapper.html($qty);
            $('#action_' + $product_id + ' .error-msg').html('');
        }

        //$this.removeClass("tooltip");
        $this.removeAttr("data-tooltip");

    } else if ($product_minimum == $qty - 1) {

        //$(document).tooltip(); 
        //$this.attr("data-tooltip","Limited quantity available. You can't add more than "+$product_minimum+" of this item");
        $this.attr("data-tooltip", "Maximum quantity per order for this product reached");

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
$(document).delegate('.mini-plus-quantity', 'click', function () {
    console.log("add without cart mini");
    $qty_wrapper = $(this).parent().find('.middle-quantity');
    $product_unit = $(this).data('unit');
    console.log($product_unit);//unit value didnt find

    $unit = '';
    $unit = $product_unit.split('-')[1];
    //    alert($unit);
    if ($unit == ' Kg') {
        $qty = parseFloat($qty_wrapper.html()) + 0.5;
    }
    else {
        $qty = parseFloat($qty_wrapper.html()) + 1;
    }

    console.log($qty_wrapper);

    $product_store_id = $(this).data('id');
    $variation_id = 0;

    $this = $(this);
    $product_id = $this.attr('data-id');

    //$qty_wrapper.html($qty);

    $product_minimum = $this.attr('data-minimum');

    //NEW CODE WITHOUT PRODUCT MAX QUANTITY RESTRICTION
    var d = cart.update($this.attr('data-key'), $qty);
    $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
    $('#action_' + $product_id + ' .error-msg').html('');
    /* Button code extened */
    $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
    $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($qty + ' items in cart <i class="fas fa-flag"></i>');
    $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
    console.log("mini click");
    $('.cart-panel-content').load('index.php?path=common/cart/newInfo', function () {


    });
    //$this.removeClass("tooltip");
    $this.removeAttr("data-tooltip");
    //NEW CODE WITHOUT PRODUCT MAX QUANTITY RESTRICTION
    if ($product_minimum >= $qty) {


        var d = cart.update($this.attr('data-key'), $qty);
        $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
        $('#action_' + $product_id + ' .error-msg').html('');

        /* Button code extened */
        $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
        $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($qty + ' items in cart <i class="fas fa-flag"></i>');
        $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");

        console.log("mini click");
        $('.cart-panel-content').load('index.php?path=common/cart/newInfo', function () {


        });

        //$this.removeClass("tooltip");
        $this.removeAttr("data-tooltip");

    } else if ($product_minimum == $qty - 1) {

        //$(document).tooltip(); 
        //$this.attr("data-tooltip","Limited quantity available. You can't add more than "+$product_minimum+" of this item");

        $this.attr("data-tooltip", "Maximum quantity per order for this product reached");




        $this.attr("data-tooltip").toLowerCase();

        console.log("max qty raecahedxz");
    }



    //console.log(d);


    //$('#cart').load('index.php?path=common/cart/info');
});
/* mini cart plus end*/
//cart module 
$(document).delegate('#cart .quantity-controller .plus', 'click', function () {


    $qty = parseInt($(this).parent().find('.num').html()) + 1;

    $product_store_id = $(this).data('id');
    $variation_id = $(this).data('variation-id');
    // alert($product_store_id);
    $this = $(this);
    $product_id = $this.data('data-id');

    if ($this.attr('data-key').length > 0) {
        cart.update($this.attr('data-key'), $qty);
        $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
        $qty_wrapper = $(document).find('#' + $product_store_id + '-' + $variation_id + ' .middle-quantity').html($qty);
        $qty_wrapper = $(document).find('.unique' + $product_store_id + '-' + $variation_id + ' .middle-quantity').html($qty);
        console.log("mini cart controller click");
        $('#cart_' + $product_store_id + ' .error-msg').html("");
        $('#action_' + $product_store_id + ' .error-msg').html("");
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

$(document).delegate('.minus-quantity', 'click', function () {


    $qty_wrapper = $(this).parent().find('.middle-quantity');

    $product_store_id = $(this).data('id');
    $variation_id = 0;


    console.log("clickv" + $product_store_id + "s" + $variation_id);
    $qty = parseInt($qty_wrapper.html()) - 1;
    $product_id = $(this).attr('data-id');
    $('#action_' + $product_id + ' .error-msg').html('');
    $this = $(this);

    $this.parent().children('.plus-quantity').removeAttr("data-tooltip");

    if ($qty > 0) {
        //update cart if exist in cart 

        if ($this.attr('data-key').length > 0) {
            cart.update($this.attr('data-key'), $qty);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
            $qty_wrapper.html($qty);
            $qty_wrapper = $(document).find('#' + $product_store_id + '-' + $variation_id + ' .middle-quantity').html($qty);
            $qty_wrapper = $(document).find('.unique_middle_button' + $product_store_id + '-' + $variation_id).html($qty);
            $qty_wrapper.html($qty);
            $('#action_' + $product_id + ' .info').css('display', 'block');
            $('#action_' + $product_id + ' .error-msg').html('');
        }

    } else {
        if ($this.attr('data-key').length > 0) {
            /* $(document).find('.unique'+$product_store_id+'-'+$variation_id).css('display','none'); */
            $(document).find('.unique_minus_button' + $product_store_id + '-' + $variation_id).css('display', 'none');
            $(document).find('.unique_middle_button' + $product_store_id + '-' + $variation_id).css('display', 'none');
            $(document).find('.unique_plus_button' + $product_store_id + '-' + $variation_id).css('display', 'none');

            $(document).find('.unique_add_button' + $product_store_id + '-' + $variation_id).removeAttr('style');
            $('.atc_' + $product_id).toggleClass('AtcButton__with_counter___3YxLq AtcButton__with_text___4C5OY ');

            console.log('.unique_add_button' + $product_store_id + '-' + $variation_id);
            console.log('cool grid');

            cart.remove($this.attr('data-key'));
            $('#action_remove_' + $product_store_id).remove();

        }
    }
});

/* mini minus cart start*/
$(document).delegate('.mini-minus-quantity', 'click', function () {

    var text = $('.checkout-modal-text').html();
    $('.checkout-modal-text').html('');
    $('.checkout-loader').show();

    $qty_wrapper = $(this).parent().find('.middle-quantity');

    $product_store_id = $(this).data('id');
    $variation_id = 0;
    console.log('minus mini cart');
    $product_unit = $(this).data('unit');
    console.log($product_unit);//unit value didnt find

    $unit = '';
    $unit = $product_unit.split('-')[1];
    //    alert($unit);
    if ($unit == ' Kg') {
        $qty = parseFloat($qty_wrapper.html()) - 0.5;
    }
    else {
        $qty = parseFloat($qty_wrapper.html()) - 1;
    }

    $product_id = $(this).attr('data-id');
    $('#action_' + $product_id + ' .error-msg').html('');
    $this = $(this);
    if ($qty > 0) {
        //update cart if exist in cart 
        /*$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
        $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);*/
        if ($this.attr('data-key').length > 0) {
            cart.update($this.attr('data-key'), $qty);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
            $('#action_' + $product_id + ' .info').css('display', 'block');
            $('#action_' + $product_id + ' .error-msg').html('');
            /* Cart Button Code extened */
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#ea7128");
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($qty + ' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "block");
        }

        console.log("mini click");
        $('.cart-panel-content').load('index.php?path=common/cart/newInfo', function () {

            console.log("mini cart click");

            $.ajax({
                url: 'index.php?path=common/home/cartDetails',
                type: 'post',
                dataType: 'json',
                success: function (json) {
                    console.log("mmon/home/cartDetails");

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
        if ($this.attr('data-key').length > 0) {
            //$(document).find('.unique'+$product_store_id+'-'+$variation_id).css('display','none');
            //$(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).removeAttr('style');
            //$(document).find('.unique_add_button'+$product_store_id+'-'+$variation_id).css('display','true');

            $(document).find('.unique_minus_button' + $product_store_id + '-' + $variation_id).css('display', 'none');
            $(document).find('.unique_middle_button' + $product_store_id + '-' + $variation_id).css('display', 'none');
            $(document).find('.unique_plus_button' + $product_store_id + '-' + $variation_id).css('display', 'none');

            $(document).find('.unique_add_button' + $product_store_id + '-' + $variation_id).removeAttr('style');
            $('.atc_' + $product_id).toggleClass('AtcButton__with_counter___3YxLq AtcButton__with_text___4C5OY ');

            console.log('.unique_add_button' + $product_store_id + '-' + $variation_id);
            console.log('cool');

            cart.remove($this.attr('data-key'));
            $('#action_remove_' + $product_store_id).remove();

            /* Code added For Remove product from cart */
            $('.unique_add_button' + $product_store_id + '-' + $variation_id).attr('data-action', 'add');
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
            $('#AtcButton-id-' + $product_id + '-' + $variation_id).css("background-color", "#3baa33");
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).html($qty + ' items in cart <i class="fas fa-flag"></i>');
            $('#flag-qty-id-' + $product_id + '-' + $variation_id).css("display", "none");

        }
    }



});
/* mini cart end*/
$(document).delegate('#cart .quantity-controller .minus', 'click', function () {

    console.log("minus quantity-controller");
    $qty = parseInt($(this).parent().find('.num').html()) - 1;
    $product_store_id = $(this).data('id');
    $variation_id = $(this).data('variation-id');
    $product_id = $(this).data('data-id');
    $('#action_' + $product_store_id + ' .error-msg').html('');
    $qty_wrapper = $(document).find('#' + $product_store_id + '-' + $variation_id + ' .middle-quantity').html($qty);
    $this = $(this);
    if ($qty > 0) {

        $qty_wrapper.html($qty);
        if ($this.attr('data-key').length > 0) {
            cart.update($this.attr('data-key'), $qty);
            $('#cart-qty-' + $product_id + '-' + $variation_id).val($qty);
            $('#action_' + $product_store_id + ' .info').css('display', 'block');
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
    if ($('#list-name').val().length > 0) {
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
            success: function (json) {
                console.log(json);



                if (json['status']) {

                    $('#list-message-success').html(json['message']);

                    console.log("erg sage-suc");
                    data = {
                        product_id: $(".listproductId").val()
                    }

                    $.ajax({
                        url: 'index.php?path=account/wishlist/getProductWislists',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function (json) {
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

    if (true) {

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
            success: function (json) {
                console.log(json);


                $('.add-in-list-modal-text').html(text);
                $('.add-in-list-loader').hide();
                if (json['status']) {

                    $('#list-message-success').html(json['message']);

                    //window.location.reload(false);
                    setTimeout(function () { $('#list-message-success').html(''); $('#list-message-error').html(''); $('#listModal').modal('hide'); }, 1500);

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


$(document).delegate('#forget-button', 'click', function () {
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
        success: function (json) {
            console.log(json);



            if (json['status']) {

                $('#forgetModal').find('form').trigger('reset');

                $('#forget-message').html('');
                //$('#forget-success-message').html(json['text_message']);
                $('#forget-success-message').html("<p style='color:green'>" + json['text_message'] + "</p>");

                $('.forget-modal-text').html(text);
                $('.forget-loader').hide();

                //setTimeout(function(){ window.location.reload(false); }, 1000);
            } else {
                $error = '';

                $('.forget-modal-text').html(text);
                $('.forget-loader').hide();

                console.log("forget-form clickxxrgre");
                if (json['text_message']) {
                    $error += json['text_message'];
                }
                //$('#forget-message').html($error);
                console.log($error);
                $('#forget-message').html("<p style='color:red'>" + $error + "</p>");

            }
        }
    });
});
$(document).delegate('#facebook-signup', 'click', function (e) {
    console.log("facebook-btn click");
    $.ajax({
        url: 'index.php?path=common/home/getFacebookRedirectUrl&redirect_url=<?= $_SERVER["REQUEST_URI"]?>',
        type: 'post',
        dataType: 'json',

        success: function (json) {
            console.log(json);
            e.preventDefault();
            location.href = json['facebook'];
        }
    });
});

$(document).delegate('#facebook-login', 'click', function (e) {
    console.log("facebook-btn click");
    $.ajax({
        url: 'index.php?path=common/home/getFacebookRedirectUrl&redirect_url=<?= $_SERVER["REQUEST_URI"]?>',
        type: 'post',
        dataType: 'json',
        success: function (json) {
            console.log(json);
            e.preventDefault();
            location.href = json['facebook'];
        }
    });
});

$(document).delegate('#login', 'click', function () {
    console.log("log click");
    var text = $('.login-modal-text').html();
    $('.login-modal-text').html('');
    $('.login-loader').show();

    $.ajax({
        url: 'index.php?path=account/login',
        type: 'post',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);
            if (json['status']) {
                window.location.reload(false);
            } else {
                $('.login-modal-text').html(text);
                $('.login-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#login-message').html("<p style='color:red'>" + json['error_warning'] + "</p>");
            }
        }
    });
});


$(document).delegate('#resendVerificationMail', 'click', function () {
    console.log("resendVerificationMail click");
    /*var text = $('.login-modal-text').html();
    $('.login-modal-text').html('');
    $('.login-loader').show();*/

    $.ajax({
        url: 'index.php?path=account/activate/resendEmail',
        type: 'post',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);
            if (json['status']) {
                $('#login-message').html("<p style='color:green'>" + json['success_message'] + "</p>");
            } else {
                /*$('.login-modal-text').html(text);
                $('.login-loader').hide();*/
                $('#login-message').html("<p style='color:red'>" + json['error_warning'] + "</p>");
            }
        }
    });
});



$(document).delegate('#reportissue', 'click', function () {
    $('#reportissue').prop('disabled', true);
    console.log("reportissue click");
    var text = $('.reportissue-modal-text').html();
    // $('.reportissue-modal-text').html('');
    $('.reportissue-loader').show();


    $.ajax({
        url: 'index.php?path=information/reportissue',
        type: 'post',
        data: $('#reportissue-form').serialize(),
        dataType: 'json',
        async: true,
        success: function (json) {
            console.log(json);
            if (json['status']) {


                $('#reportissue-message').html('');
                $('#reportissue-success-message').html(json['text_message']);

                // $('.reportissue-modal-text').html(text);
                $('.reportissue-loader').hide();


                $('#reportissueModal').find('form').trigger('reset');
                $('.reportissue-loader').hide(); $('#reportissue').prop('disabled', false);


            } else {
                $error = '';
                $('#reportissue-success-message').html('');

                if (json['error_issuesummary']) {
                    $error += json['error_issuesummary'] + '<br/>';
                }


                // $('.reportissue-modal-text').html(text);
                $('.reportissue-loader').hide(); $('#reportissue').prop('disabled', false);

                $('#reportissue-message').html("<p style='color:red'>" + $error + "</p>");
            }
        }
    });
});

$(document).delegate('#contactus', 'click', function () {
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
        success: function (json) {
            console.log(json);
            if (json['status']) {
                $('#contactus-message').html('');
                $('#contactus-success-message').html(json['text_message']);

                $('.contact-modal-text').html(text);
                $('.contact-loader').hide();


                $('#contactusModal').find('form').trigger('reset');

            } else {
                $error = '';

                if (json['error_email']) {
                    $error += json['error_email'] + '<br/>';
                }
                if (json['error_enquiry']) {
                    $error += json['error_enquiry'] + '<br/>';
                }
                if (json['error_name']) {
                    $error += json['error_lastname'] + '<br/>';
                }

                $('.contact-modal-text').html(text);
                $('.contact-loader').hide();

                $('#contactus-message').html("<p style='color:red'>" + $error + "</p>");
            }
        }
    });
});


function ValidateEmail(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm.emailAddr.value)) {
        return (true)
    }
    alert("You have entered an invalid email address!")
    return (false)
}

/*$(document).delegate('#login_send_otp', 'click', function() {
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
*/

$(document).delegate('#login_send_otp', 'click', function () {
    console.log("login_send_otp click");


    if (($('#login-form #email').val().length > 0) && ($('#login-form #password').val().length > 0)) {

        var text = $('.login-modal-text').html();
        //$('.login-modal-text').html('');
        $('.login-loader').show();

        $.ajax({
            url: 'index.php?path=account/login/login',
            type: 'post',
            data: $('#login-form').serialize(),
            dataType: 'json',
            success: function (json) {
                console.log(json);
                if (json['status']) {


                    //$('.login-modal-text').html(text);
                    $('.login-loader').hide();
                    //$('#login-message').html(json['error_warning']);
                    //$('#login-message').html("<p style='color:green'>"+json['success_message']+"</p>");
                    //window.setTimeout(function(){location.reload()},2000)
                    if (json['temppassword'] == "1") {
                        location = $('.base_url').attr('href') + "/changepass";
                        console.log($('.base_url'));
                    }
                    else {
                        location = $('.base_url').attr('href');

                    }


                    //$('#customer_id').val(json['customer_id']);

                    /*var divs = $('.mydivsss>div');
                    var now = 0; // currently shown div
                    
                    divs.eq(now).hide();
                    now = (now + 1 < divs.length) ? now + 1 : 0;
                    divs.eq(now).show(); // show otp next
                    */


                } else {
                    //$('.login-modal-text').html(text);
                    $('.login-loader').hide();
                    //$('#login-message').html(json['error_warning']);
                    $('#login-message').html("<p style='color:red'>" + json['error_warning'] + "</p>");
                }
            }
        });
    } else {
        $('#login-form #email').focus();
    }

});

$(document).delegate('#login_resend_otp', 'click', function () {
    console.log("login_resend_otp click");
    var text = $('.login-modal-text').html();
    $('.login-modal-text').html('');
    $('.login-loader').show();



    $.ajax({
        url: 'index.php?path=account/login/login_send_otp',
        type: 'post',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);
            if (json['status']) {

                $('.login-modal-text').html(text);
                $('.login-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#otp-message').html("<p style='color:green'>" + json['success_message'] + "</p>");

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
                $('#login-message').html("<p style='color:red'>" + json['error_warning'] + "</p>");
            }
        }
    });
});



$(document).delegate('#login_verify_otp', 'click', function () {
    console.log("login_verify_otp click");
    var text = $('.login-otp-modal-text').html();
    $('.login-otp-modal-text').html('');
    $('.login-otp-loader').show();

    $.ajax({
        url: 'index.php?path=account/login/login_verify_otp',
        type: 'post',
        data: $('#login-otp-form').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);

            if (json['status']) {
                window.location.reload(false);
            } else {
                $('.login-otp-modal-text').html(text);
                $('.login-otp-loader').hide();
                //$('#login-message').html(json['error_warning']);
                $('#otp-message').html("<p style='color:red'>" + json['error_warning'] + "</p>");
            }
        }
    });
});

$(document).delegate('#signup', 'click', function () {
    console.log('22222222222222222222222222222222');


    // console.log("response",grecaptcha.getResponse());
    console.log($('#register_verify_otp').val());
    console.log($('input[name="agree_checkbox"]:checked').length);
    var checkCaptha = false;
    /* if($('input[name="agree_checkbox"]:checked').length)
     {*/

    // $('#error_agree').show();
    $('#error_captha').hide();
    $('span.text-danger').remove();
    $(".formui").removeClass('error-animation');
    $('#error_agree').hide();

    var text = $('.signup-modal-text').html();
    //$('.signup-modal-text').html('');
    $('.signup-loader').show();

    $('#signup-message').html('please wait...');

    console.log($('#register_verify_otp').val());

    if ($('#register_verify_otp').val() == 'yes') {

        var url = 'index.php?path=account/register/register_verify_otp';
    } else {
        var url = 'index.php?path=account/register/register_send_otp';
        checkCaptha = true;
    }

    if (checkCaptha == true && (grecaptcha.getResponse() == "")) {
        $('#error_captha').show();
        return;
    }

    $.ajax({
        url: url,
        type: 'post',
        data: $('#sign-up-form').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);
            console.log('signup return');
            $('.reg_bg').removeClass('.heightset');

            if (json['status']) {


                $('#signup-message').html('<p style="color:green;margin-top:60px"> ' + json['success_message'] + '</p>');



                if ($('#register_verify_otp').val() == 'yes') {

                    $('.signup-modal-text').html(text);
                    //$('.signup_otp_div').hide();
                    //$('#signup').hide();
                    $('#signup-message>p').css({ "margin-top": "150px", "font-size": "24px" });

                    setTimeout(function () {
                        location = $('.base_url').attr('href');
                    }, 5000);
                    ///window.location.reload(false);
                    // Redirect To Profile Page
                    // var baseurl = window.location.origin+window.location.pathname;
                    // location.href = baseurl+'?path=account/profileinfo';
                } else {
                    $('.signup_otp_div').show();
                    $('#other_signup_div').hide();
                    $('p#already_register').hide();
                    $('.reg_bg').removeClass('.heightset');
                    // button text to read verify otp
                    $('.signup-modal-text').html(json['text_verify_otp']);

                }

                $('.signup-loader').hide();

                $('#register_verify_otp').val('yes');


                return false;
            } else {
                $('#signup-message').remove();
                //console.log(json,'json_response');
                //alert('dddd');
                /*if($('#register_verify_otp').val() == 'yes') {
                    $('#register_verify_otp').val('no');     
                }*/
                var $form = $("form[id='sign-up-form']")

                $error = '';

                if (json['error_email']) {
                    //$error += json['error_email']+'<br/>';
                    $form.find("input[name='email']").after('<span class="text-danger fa fa-star">' + json['error_email'] + '</span>');
                    $form.find("input[name='email']").parent().addClass('error-animation');
                }
                if (json['error_firstname']) {
                    //$error += json['error_firstname']+'<br/>';
                    $form.find("input[name='firstname']").after('<span class="text-danger fa fa-star">' + json['error_firstname'] + '</span>');
                    $form.find("input[name='firstname']").parent().addClass('error-animation');
                }
                if (json['error_telephone_exists']) {
                    //$error += json['error_telephone_exists']+'<br/>';
                    $form.find("input[name='telephone']").after('<span class="text-danger fa fa-star">' + json['error_telephone_exists'] + '</span>');
                    $form.find("input[name='telephone']").parent().addClass('error-animation');
                }

                if (json['error_lastname']) {
                    //$error += json['error_lastname']+'<br/>';
                    //$error += json['error_lastname']+'<br/>';
                    $form.find("input[name='lastname']").after('<span class="text-danger fa fa-star">' + json['error_lastname'] + '</span>');
                    $form.find("input[name='lastname']").parent().addClass('error-animation');
                }

                if (json['error_telephone']) {
                    //$error += json['error_telephone']+'<br/>';
                    $form.find("input[name='telephone']").after('<span class="text-danger fa fa-star">' + json['error_telephone'] + '</span>');
                    $form.find("input[name='telephone']").parent().addClass('error-animation');
                }
                if (json['error_dob']) {
                    $error += json['error_dob'] + '<br/>';
                }
                if (json['error_gender']) {
                    $error += json['error_gender'] + '<br/>';
                }
                if (json['error_tax']) {
                    $error += json['error_tax'] + '<br/>';
                }

                if (json['error_password']) {
                    //$error += json['error_password']+'<br/>';
                    $form.find("input[name='password']").after('<span class="text-danger fa fa-star">' + json['error_password'] + '</span>');
                    $form.find("input[name='password']").parent().addClass('error-animation');
                }

                if (json['error_confirm']) {
                    //$error += json['error_confirm']+'<br/>';
                    $form.find("input[name='confirm']").after('<span class="text-danger fa fa-star">' + json['error_confirm'] + '</span>');
                    $form.find("input[name='confirm']").parent().addClass('error-animation');
                }
                if (json['error_match_password']) {
                    //$error += json['error_match_password']+'<br/>';
                    $form.find("input[name='confirm']").after('<span class="text-danger fa fa-star">' + json['error_match_password'] + '</span>');
                    $form.find("input[name='confirm']").parent().addClass('error-animation');
                }

                if (json['error_company_name']) {
                    //$error += json['error_company_name_address']+'<br/>';
                    $form.find("input[name='company_name']").after('<span class="text-danger fa fa-star">' + json['error_company_name'] + '</span>');
                    $form.find("input[name='company_name']").parent().addClass('error-animation');
                }

                if (json['error_company_address']) {
                    //$error += json['error_company_name_address']+'<br/>';
                    $form.find("input[name='company_address']").after('<span class="text-danger fa fa-star">' + json['error_company_address'] + '</span>');
                    $form.find("input[name='company_address']").parent().addClass('error-animation');
                }

                /*if(json['error_address']){
                     //$error += json['error_company_name_address']+'<br/>';
                     $form.find( "input[name='address']" ).after('<span class="text-danger fa fa-star">'+json['error_address']+'</span>');
                     $form.find( "input[name='address']" ).parent().addClass('error-animation');
                 }*/

                if (json['error_house_building']) {
                    //$error += json['error_company_name_address']+'<br/>';
                    $form.find("input[name='house_building']").after('<span class="text-danger fa fa-star">' + json['error_house_building'] + '</span>');
                    $form.find("input[name='house_building']").parent().addClass('error-animation');
                }

                if (json['error_location']) {
                    //$error += json['error_company_name_address']+'<br/>';
                    $form.find("input[name='location']").after('<span class="text-danger fa fa-star">' + json['error_location'] + '</span>');
                    $form.find("input[name='location']").parent().addClass('error-animation');
                }


                if (json['error_company_name_address']) {
                    //$error += json['error_company_name_address']+'<br/>';
                    $form.find("input[name='company_name']").after('<span class="text-danger fa fa-star">' + json['error_company_name_address'] + '</span>');
                    $form.find("input[name='company_name']").parent().addClass('error-animation');
                }

                if (json['error_warning']) {
                    $error += json['error_warning'] + '<br/>';
                    $('#signup-message').html("<p style='color:red;margin-top:60px;'>" + $error + "</p>");
                }

                $('.signup-modal-text').html(text);
                $('.signup-loader').hide();
                $('.reg_bg').addClass('heightset');

            }
        }
    });
    /*} else{
      // unchecked
      console.log("nucheck error");
      $('#error_captha').show();
      //$('#error_agree').html($('#error_agree_text').val());
    }*/
});
/*$(document).delegate('#signup', 'click', function() {

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

        var url = 'index.php?path=account/register/register';


        $.ajax({
            url: url,
            type: 'post',
            data: $('#sign-up-form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                console.log('signup return');


                if (json['status']) {

                    
                   $('#signup-message').html('<p style="color:green"> '+ json['message']+'</p>');
                    
                   

                    /*if($('#register_verify_otp').val() == 'yes') {

                        $('.signup-modal-text').html(text);
                      
                        ///window.location.reload(false);
                        // Redirect To Profile Page
                        var baseurl = window.location.origin+window.location.pathname;
                        location.href = baseurl+'index.php?path=account/profileinfo';
                    } else {
                        $('.signup_otp_div').show();
                        $('#other_signup_div').hide();
                        // button text to read verify otp
                        $('.signup-modal-text').html(json['text_verify_otp']);

                    }*/
//alert('redirect');
/* var baseurl = window.location.origin+window.location.pathname;
 location.href = baseurl+'index.php?path=account/profileinfo';

 $('.signup-loader').hide();

 //$('#register_verify_otp').val('yes');
 
 
 //return false;
 
} else {   

 /*if($('#register_verify_otp').val() == 'yes') {
     $('#register_verify_otp').val('no');     
 }*/


/* $error = '';

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
 if(json['error_confirm']){
     $error += json['error_confirm']+'<br/>';
 }
 if(json['error_match_password']){
     $error += json['error_match_password']+'<br/>';
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
*/

$(document).delegate('#signup-resend-otp', 'click', function () {

    console.log('111111111111111111111');

    var text = $('.signup-modal-text').html();
    //$('.signup-modal-text').html('');
    $('.signup-loader').show();

    $('#signup-message').html('please wait...');

    console.log($('#register_verify_otp').val());

    var url = 'index.php?path=account/register/register_send_otp';

    $.ajax({
        url: url,
        type: 'post',
        data: $('#sign-up-form').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);
            console.log('signup return');


            if (json['status']) {


                $('#signup-message').html('<p style="color:green;margin-top:60px"> ' + json['success_message'] + '</p>');


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

                if (json['error_email']) {
                    $error += json['error_email'] + '<br/>';
                }
                if (json['error_firstname']) {
                    $error += json['error_firstname'] + '<br/>';
                }
                if (json['error_telephone_exists']) {
                    $error += json['error_telephone_exists'] + '<br/>';
                }

                if (json['error_lastname']) {
                    $error += json['error_lastname'] + '<br/>';
                }
                if (json['error_telephone']) {
                    $error += json['error_telephone'] + '<br/>';
                }
                if (json['error_dob']) {
                    $error += json['error_dob'] + '<br/>';
                }
                if (json['error_gender']) {
                    $error += json['error_gender'] + '<br/>';
                }
                if (json['error_tax']) {
                    $error += json['error_tax'] + '<br/>';
                }
                if (json['error_password']) {
                    $error += json['error_password'] + '<br/>';
                }
                if (json['error_warning']) {
                    $error += json['error_warning'] + '<br/>';
                }

                $('.signup-modal-text').html(text);
                $('.signup-loader').hide();
                $('#signup-message').html("<p style='color:red'>" + $error + "</p>");
            }
        }
    });
});





$(document).delegate('#registerfarmer', 'click', function () {


    console.log("aasdfasf");
    $('span.text-danger').remove();
    $(".formui").removeClass('error-animation');
    $('#error_agree').hide();

    //  var text = $('.signup-modal-text').html();         
    //  $('.signup-loader').show(); 
    //  $('#signup-message').html('please wait...');

    var url = 'index.php?path=account/farmerregister/register';
    console.log(url);
    console.log($('#registerForm').serialize());


    $('#error_captha').hide();

    if ((grecaptcha.getResponse() == "")) {
        $('#error_captha').show();
        return;
    }

    $.ajax({
        url: url,
        type: 'post',
        data: $('#registerForm').serialize(),
        dataType: 'json',
        success: function (json) {
            console.log(json);
            console.log('farmerregister return');
            //  $('.reg_bg').removeClass('.heightset');

            if (json['status']) {

                $('#signup-message').html('<p style="color:green;margin-top:60px"> ' + json['success_message'] + '</p>');
                $("#registerForm")[0].reset();

                //  $('.signup-modal-text').html(text); 
                //  $('#signup-message>p').css({"margin-top": "150px", "font-size": "24px"});

                //  setTimeout(function() {
                //      location = $('.base_url').attr('href');
                //  }, 5000);
                //  window.location.reload(false);
                //  // Redirect To some Page
                // var baseurl = window.location.origin+window.location.pathname;
                // location.href = baseurl+'?path=account/login/farmer';


                //  $('.signup-loader').hide(); 

                return false;
            } else {
                //  $('#signup-message').remove();

                var $form = $("form[id='registerform']")

                $error = '';

                if (json['error_email']) {
                    $error += json['error_email'] + '<br/>';
                    $form.find("input[name='email']").after('<span class="text-danger fa fa-star">' + json['error_email'] + '</span>');
                    $form.find("input[name='email']").parent().addClass('error-animation');
                }
                if (json['error_name']) {
                    $error += json['error_name'] + '<br/>';

                    $form.find("input[name='name']").after('<span class="text-danger fa fa-star">' + json['error_name'] + '</span>');
                    $form.find("input[name='name']").parent().addClass('error-animation');
                }
                if (json['error_telephone_exists']) {
                    $error += json['error_telephone_exists'] + '<br/>';
                    $form.find("input[name='telephone']").after('<span class="text-danger fa fa-star">' + json['error_telephone_exists'] + '</span>');
                    $form.find("input[name='telephone']").parent().addClass('error-animation');
                }



                if (json['error_telephone']) {
                    $error += json['error_telephone'] + '<br/>';
                    $form.find("input[name='telephone']").after('<span class="text-danger fa fa-star">' + json['error_telephone'] + '</span>');
                    $form.find("input[name='telephone']").parent().addClass('error-animation');
                }


                if (json['error_address']) {

                    $form.find("input[name='address']").after('<span class="text-danger fa fa-star">' + json['error_address'] + '</span>');
                    $form.find("input[name='address']").parent().addClass('error-animation');
                }



                if (json['error_warning']) {
                    $error += json['error_warning'] + 'test' + '<br/>';
                    $('#signup-message').html("<p style='color:red;margin-top:60px;'>" + $error + "</p>");
                }

                //  $('.signup-modal-text').html(text);
                //  $('.signup-loader').hide();
                //  $('.reg_bg').addClass('heightset');
                $('#signup-message').html("<p style='color:red;margin-top:60px;'>" + $error + "</p>");


            }
        }
    });

});