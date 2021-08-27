 

  
  
  <div class="_2D2lC">
                                                            <div class="price-popup" id="content-container">
                                                                 <?=$product['variations'][0]['special_price'];?></div>
                                                        </div>


                                                 <div class="variation-selector-container" style="width: 295px;">
                                                      <p class="variations-title" style="margin-left: -10px; display: none;"> variants</p>
                                                      <?php if(is_array($product['variations']) && count($product['variations']) > 1) { ?>
                                                      <select class="product-variation">
                                                      <?php foreach($product['variations'] as $variation) { ?>
                                                      <option value="<?php echo isset($variation['variation_id']) ? $variation['variation_id'] : ''; ?>"
                                                      data-price="<?php echo isset($variation['price']) ? $variation['price'] : ''; ?>"
                                                      data-quantity="<?php echo isset($variation['qty_in_cart']) ? $variation['qty_in_cart'] : ''; ?>"
                                                      data-key="<?php echo isset($variation['key']) ? $variation['key'] : ''; ?>"
                                                       data-productid="<?= $variation['product_id'] ?>"
                                                       data-isWl="<?= $variation['isWishListID'] ?>"
                                                      data-special="<?php echo isset($variation['special_price']) ? $variation['special_price'] : ''; ?>"
                                                      <?php if(isset($variation['category_pricing_variant_status']) && $variation['category_pricing_variant_status'] == 0) { echo "disabled"; } ?> >
                                                      <?php  echo 'Per ' . $variation['unit']; ?>
                                                      </option>
                                                      <?php } ?>
                                                      </select>
                                                      <?php } ?>
                                                      <?php if(is_array($product['variations']) && count($product['variations']) == 1) { ?>
                                                      <span id="variationsunitname"><?php  echo 'Per ' . $product['variations'][0]['unit']; ?></span>
                                                      <?php } ?>
                                                  </div>
												  <?php 
												  //echo '<pre>';echo count($product['produce_type']);exit;
												  if(is_array($product['produce_type']) && count($product['produce_type'])>0){?>
												  <div class="variation-selector-container" style="width: 295px;">
                                                      <p class="variations-title" style="margin-left: -10px; display: none;"> variants</p>
                                                      <select name="produce-type" class="produce-type" data-defaultquantity="<?php echo $product['qty_in_cart']?>">
													  <!--<option value="" data-defaultquantity="<?php echo $product['qty_in_cart']?>"> Select Produce Type </option>-->
                                                      <?php foreach($product['produce_type'] as $type) { if($type['value']>0) { ?>
                                                      <option value="<?php echo $type['type']; ?>" selected   datavalue="<?php echo $type['value']; ?>">
                                                      <?php echo $type['type']; ?>  
                                                      </option>
                                                        <?php } else { ?>
                                                          
                                                    <option value="<?php echo $type['type']; ?>"    datavalue="<?php echo $type['value']; ?>">
                                                      <?php echo $type['type']; ?> 
                                                      </option>
                                                            <?php } ?>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
												  <?php } ?>
												 
                                                 
 
<div class="sp-quantity" class="qtybtns-addbtnd" id="controller-container">

    <p class="info"><?php if(isset($text_incart)) $text_incart ?></p>       
    <!--<p class="error-msg" ></p>-->
</div>
<div class="col-md-5">
<div class="qtybtns-addbtnd addcart-block" id="add-btn-container">
 <input type="text" product_store_id="<?= $product['product_store_id'] ?>" onkeypress="return validateFloatKeyPress(this, event);" autocomplete="off"  style="margin-left: -15px;" class="input-cart-qty" id="cart-qty-<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" value="<?php if($product['qty_in_cart']>0){echo $product['qty_in_cart'];}?>" placeholder="Add Qty">
 <a id="AtcButton-id-<?= $product['store_product_variation_id'] ?>" style="<?php if($product['qty_in_cart']>0){echo "background-color:#ea7128";}?>" class="AtcButton__container___1RZ9c AtcButton__with_counter___3YxLq atc_ AtcButton__small___1a1kH" >
 <span data-action="<?= $product['qty_in_cart'] ? 'update' : 'add'; ?>"
       data-key='<?= $product["key"] ?>'
       class="AtcButton__button_text___VoXuy unique_add_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"
       id="add-cart-btnnew"
       data-store-id="<?= $product['store_id'] ?>"
       data-variation-id="<?= $product['store_product_variation_id'] ?>"
       data-id="<?= $product['product_store_id'] ?>"      
       data-producetype-id=""       
       style="display: <?= $product['qty_in_cart'] ? 'block' : 'block'; ?>"  data-dismiss="modal">
 <i class="fas fa-cart-plus"></i>
 </span>
 </a>
 
</div>
</div>



 <div class="variation-selector-container" style="width: 275px;">
	<textarea name="product_notes" class="form-control" maxlength="200" placeholder="Product Notes" id="product_notes" style="height: 50px; margin-top:10px;"><?php if(isset($product['product_note']) && $product['product_note'] != NULL){echo $product['product_note'];}?></textarea>
 </div>



<!--
REMOVED WISHLIST
<div class="sp-quantity" class="qtybtns-addbtnd" id="controller-container">
    
    <?php if ($product['qty_in_cart']) { ?> 

    <a class=" AtcButton_container_popup AtcButton__container___1RZ9c AtcButton__with_counter___3YxLq atc_<?= $product['product_store_id'] ?> AtcButton__small___1a1kH">

    <span class="AtcButton__button_text___VoXuy unique_add_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="add-btn" data-variation-id="<?= $product['store_product_variation_id'] ?>" data-id="<?= $product['product_store_id'] ?>" style="display: <?= $product['qty_in_cart'] ? 'none' : 'block'; ?>"><?php if(isset($button_add)) echo $button_add ?></span>

        <span class="AtcButton__decrement_button___2ov_L minus-quantity unique_minus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="4" viewBox="0 0 12 2"><path d="M0 0h14v2H0z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

        <span class="AtcButton__counter___iR7_X middle-quantity unique_middle_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" id="YToyOntzOjE2OiJwcm9kdWN0X3N0b3JlX2lkIjtpOjI3MDM0O3M6ODoic3RvcmVfaWQiO3M6MToiMiI7fQ==" style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>"><?= $product['qty_in_cart']?></span>

        <span class="AtcButton__increment_button___3j3y8 plus-quantity unique_plus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-minimum='<?= $product["minimum"] ?>' data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="16" viewBox="0 0 12 12"><path d="M5 12V7H0V5h5V0h2v5h5v2H7v5z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

     </a>
    
    <?php } else { ?>
    

        <a class=" AtcButton_container_popup AtcButton__container___1RZ9c AtcButton__with_text___4C5OY atc_<?= $product['product_store_id'] ?> AtcButton__small___1a1kH">

        <span class="AtcButton__button_text___VoXuy unique_add_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" id="add-btn" data-variation-id="<?= $product['store_product_variation_id'] ?>" data-id="<?= $product['product_store_id'] ?>" style="display: <?= $product['qty_in_cart'] ? 'none' : 'block'; ?>"><?php if(isset($button_add)) echo $button_add ?></span>

        <span class="AtcButton__decrement_button___2ov_L minus-quantity unique_minus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="4" viewBox="0 0 12 2"><path d="M0 0h14v2H0z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

        <span class="AtcButton__counter___iR7_X middle-quantity unique_middle_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" id="YToyOntzOjE2OiJwcm9kdWN0X3N0b3JlX2lkIjtpOjI3MDM0O3M6ODoic3RvcmVfaWQiO3M6MToiMiI7fQ==" style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">1</span>

        <span class="AtcButton__increment_button___3j3y8 plus-quantity unique_plus_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"" data-minimum='<?= $product["minimum"] ?>' data-key='<?= $product["key"] ?>' data-id='<?= $product["product_store_id"] ?>' style="display: <?= $product['qty_in_cart'] ? 'flex' : 'none'; ?>">
            <svg width="12" height="16" viewBox="0 0 12 12"><path d="M5 12V7H0V5h5V0h2v5h5v2H7v5z" fill="#FFF" fill-rule="evenodd"></path></svg>
        </span>

        </a>

     <?php } ?>  
</div>
-->



<script>
function isNumberKey(txt, evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode == 46) {
        //Check if the text already contains the . character
        
        if (txt.value.indexOf('.') === -1  ) {
          return true;
        } else {
          return false;
        }
      } else {
         

        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
      }
      return true;
    }

    function validateFloatKeyPress(el, evt) {
       $optionvalue=$('.product-variation option:selected').text().trim();
       if($optionvalue == null || $optionvalue == '' || $optionvalue == undefined) {
       console.log($('#variationsunitname').html());
       var unit_name = $('#variationsunitname').html();
       var unit_name = unit_name.trim();
       $optionvalue = unit_name;
       }
       console.log($optionvalue);
       //alert($optionvalue);
       if($optionvalue=="Per Kg")
       {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
       }

       else{
 var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
          else
      
      return true;
       }
}


function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}
 

$(function() {

   $('.input-cart-qty').on("cut copy paste",function(e) {
      e.preventDefault();
   });
    $("select.product-variation").prop('disabled', function() {
        return $('option', this).length < 2;
    });
    if($(".produce-type"). length)
    {
   $optionvalue=$('.produce-type option:selected').attr("datavalue");
 // alert(optionvalue);
   // $quantity= $('.produce-type option[value=optionvalue]').attr("datavalue");
     //alert($quantity); 
    let productQuantityInput = $('.input-cart-qty'); 
    if($optionvalue>0)
         productQuantityInput.val($optionvalue); 
      let dataHolder = $('#add-cart-btnnew');
     dataHolder.attr('data-action', 'add'); 

    }
});


$(document).delegate('.product-variation', 'change', function() {

     
    const newProductId = $(this).children("option:selected").val();
    const newPrice = $(this).children("option:selected").attr('data-price');
    const newproID = $(this).children("option:selected").attr('data-productid');
    const newwlID = $(this).children("option:selected").attr('data-isWl');
    const newSpecial = $(this).children("option:selected").attr('data-special');
    const dataKey = $(this).children("option:selected").attr('data-key');
    const qty_in_cart1 = $(this).children("option:selected").attr('data-quantity');

    // TODO: Change trailing -0 to variations_id?
    const newQuantityInputId = 'cart-qty-' + newProductId + '-0';
   //const newcartId = 'AtcButton-id-' + newProductId + '-0';
     //$('#content-container').html('KES ' +newSpecial);
     //$('#content-container').html('KES '  +qty_in_cart1);
     $('#content-container').html(newSpecial);
 
    let dataHolder = $('#add-cart-btnnew');
    let wishlistHolder = $('#add-wishlist');
     let wishlistbuttonHolder = $('#add-btn-wishlist');
     let productQuantityInput = $('.input-cart-qty');    
     //let newcartcontrol = $('.AtcButton__container___1RZ9c AtcButton__with_counter___3YxLq atc_ AtcButton__small___1a1kH');    
    // newcartcontrol.attr('id', newcartId);

    wishlistHolder.attr('data-id', newproID);
      //let newwlqtId = $('#WishlistButton-id-0'); 
    if(newwlID==1)
    {
      
 wishlistHolder.text("Added To List");
  wishlistHolder.attr('data-action','delete');  
  wishlistbuttonHolder.attr('style','background-color:#ea7128');
    }
    else{
wishlistHolder.text("Add To My List");
  wishlistHolder.attr('data-action','add');  
    wishlistbuttonHolder.attr('style','background-color:grey');




    }

 let newcartId = $('#AtcButton-id-0');  
    if(qty_in_cart1>0)
    {
    productQuantityInput.val(qty_in_cart1); 
    dataHolder.attr('data-action', 'update');
     newcartId.attr('style','background-color:#ea7128');

    }
    else{
    
    productQuantityInput.val(''); 
    dataHolder.attr('data-action', 'add');
      newcartId.attr('style','');

    }    
     productQuantityInput.attr('id', newQuantityInputId);
  
    dataHolder.attr('data-id', newProductId);
    dataHolder.attr('data-key', dataKey);
    
     if($(".produce-type"). length)
    {
         dataHolder.attr('data-action', 'add');
    }


});



$(document).delegate('.produce-type', 'change', function() {

     
    const newProduceTypeId = $(this).children("option:selected").val(); 
    //alert(newProduceTypeId);
    const oldquantity = $(this).children("option:selected").attr('datavalue');
   // const mainquantity = $(this).children("option:selected").attr('data-defaultquantity');
    const mainquantity = $('.produce-type').attr('data-defaultquantity');
    //alert(mainquantity);

    let dataHolder = $('#add-cart-btnnew');  
    
    let productQuantityInput = $('.input-cart-qty');   
    
    let newcartId = $('#AtcButton-id-0'); 
    if(newProduceTypeId!="" && newProduceTypeId !=null)
    {

    if(oldquantity>0)   
    {
    productQuantityInput.val(oldquantity); 
    // dataHolder.attr('data-action', 'update');
    newcartId.attr('style','background-color:#ea7128');
     }
     else{
    
     productQuantityInput.val(''); 
     //dataHolder.attr('data-action', 'add');
     newcartId.attr('style','');

     }    
     dataHolder.attr('data-action', 'add'); 
    }
    else{
          
        productQuantityInput.val(mainquantity); 
        if(mainquantity>0)
        dataHolder.attr('data-action', 'update');
        else
        dataHolder.attr('data-action', 'add');

    }
    


});


$('#add-wishlist').on('click', function() {
		 $product_id = $(this).attr('data-id');  
		 $action = $(this).attr('data-action');  
        
 // alert( $product_id);
 //let newcartId = $('#WishlistButton-id-0'); 
 let wishlistbuttonHolder = $('#add-btn-wishlist');
if($action=='add')
{
    $(this).text("Added To List");
  $(this).attr('data-action','delete');
  wishlistbuttonHolder.attr('style','background-color:#ea7128');

  $('.product-variation option:selected').attr("data-iswl",1);

  
			$.ajax({
				url: 'index.php?path=account/wishlist/createWishlist&listproductId='+$product_id,
				dataType: 'html',                
				beforeSend: function() {
					 
				},
				complete: function() {
				 
				},
				success: function(html) {
					 

				}
			});
}
else{
  $(this).text("Add To My List");

  $(this).attr('data-action','add');
  wishlistbuttonHolder.attr('style','background-color:grey');


  $('.product-variation option:selected').attr("data-iswl",0);

    $.ajax({
				url: 'index.php?path=account/wishlist/deleteWishlistProductByID&listproductId='+$product_id,
				dataType: 'html',                
				beforeSend: function() {
					 
				},
				complete: function() {
				 
				},
				success: function(html) {
 

					   
				}
			});

}
			 
		});

</script>

<style>

.newui {
    border-radius: 0 2px 2px 0; 
    border-color: black;
    font-size: 12px;
    font-weight: 400;    
}
 

.rating {
    color: #000;
    font-size: 20px;
    font-weight: 300;
    text-align: center;
    text-transform: uppercase;
    position: relative;
      margin: 0; 
      position: unset;
      
}

.product-variation
{
  width:200px;
  height:40px;
  border-radius: 0;
  border-color: black;
}

.produce-type
{
  width:200px;
  height:40px;
  border-radius: 0;
  border-color: black;
}
</style>

