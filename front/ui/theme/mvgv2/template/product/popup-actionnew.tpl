 
  <div class="_2D2lC">
                                                            <div class="price-popup" id="content-container">
                                                                 <?= "KES " . $product['variations'][0]['special_price'];?></div>
                                                        </div>


                                                 <div class="variation-selector-container" style="width: 250px;">
                                                      <p class="variations-title" style="margin-left: -10px; display: none;"> variants</p>
                                                      <select class="product-variation">
                                                      <?php foreach($product['variations'] as $variation) { ?>
                                                      <option value="<?php echo $variation[variation_id]; ?>"
                                                      data-price="<?php echo $variation[price]; ?>"
                                                      data-quantity="<?php echo $variation[qty_in_cart]; ?>"
                                                      data-key="<?php echo $variation[key]; ?>"
                                                      data-special="<?php echo $variation[special_price]; ?>">
                                                      <?php  echo 'Per ' . $variation['unit']; ?>
                                                      </option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
												  <?php 
												  //echo '<pre>';echo count($product['produce_type']);exit;
												  if(count($product['produce_type'])>0){?>
												  <div class="variation-selector-container" style="width: 250px;">
                                                      <p class="variations-title" style="margin-left: -10px; display: none;"> variants</p>
                                                      <select name="produce-type" class="produce-type">
													  <option value=""> Select Produce Type </option>
                                                      <?php foreach($product['produce_type'] as $type) { ?>
                                                      <option value="<?php echo $type; ?>">
                                                      <?php echo $type; ?>
                                                      </option>
                                                      <?php } ?>
                                                      </select>
                                                  </div>
												  <?php } ?>
												 
                                                 
 
<div class="sp-quantity" class="qtybtns-addbtnd" id="controller-container">

    <p class="info"><?php if(isset($text_incart)) $text_incart ?></p>       
    <!--<p class="error-msg" ></p>-->
</div>
<div class="qtybtns-addbtnd addcart-block" id="add-btn-container">
 <input type="text" class="input-cart-qty" id="cart-qty-<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" value="<?php if($product['qty_in_cart']>0){echo $product['qty_in_cart'];}?>" placeholder="Add Qty">
 <a id="AtcButton-id-<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>" style="<?php if($product['qty_in_cart']>0){echo "background-color:#ea7128";}?>" class="AtcButton__container___1RZ9c AtcButton__with_counter___3YxLq atc_<?= $product['product_store_id'] ?> AtcButton__small___1a1kH" >
 <span data-action="<?= $product['qty_in_cart'] ? 'update' : 'add'; ?>"
       data-key='<?= $product["key"] ?>'
       class="AtcButton__button_text___VoXuy unique_add_button<?= $product['product_store_id'] ?>-<?= $product['store_product_variation_id'] ?>"
       id="add-cart-btnnew"
       data-store-id="<?= ACTIVE_STORE_ID ?>"
       data-variation-id="<?= $product['store_product_variation_id'] ?>"
       data-id="<?= $product['product_store_id'] ?>"
       style="display: <?= $product['qty_in_cart'] ? 'block' : 'block'; ?>"  data-dismiss="modal">
 <i class="fas fa-cart-plus"></i>
 </span>
 </a>
 
</div>

 <div class="variation-selector-container" style="width: 250px;">
	<textarea name="product_notes" class="form-control" maxlength="200" placeholder="Product Notes" id="product_notes" style="height: 50px; margin-top:10px;"></textarea>
 </div>



<!--

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

$(function() {
    $("select.product-variation").prop('disabled', function() {
        return $('option', this).length < 2;
    });
});


$(document).delegate('.product-variation', 'change', function() {

     
    const newProductId = $(this).children("option:selected").val();
    const newPrice = $(this).children("option:selected").attr('data-price');
    const newSpecial = $(this).children("option:selected").attr('data-special');
    const dataKey = $(this).children("option:selected").attr('data-key');
    const qty_in_cart1 = $(this).children("option:selected").attr('data-quantity');

    // TODO: Change trailing -0 to variations_id?
    const newQuantityInputId = 'cart-qty-' + newProductId + '-0';
     $('#content-container').html('KES ' +newSpecial);
     //$('#content-container').html('KES '  +qty_in_cart1);
 
    let dataHolder = $('#add-cart-btnnew');
     let productQuantityInput = $('.input-cart-qty');    
    if(qty_in_cart1>0)
    {
    productQuantityInput.val(qty_in_cart1); 
    dataHolder.attr('data-action', 'update');

    }
    else{
    
    productQuantityInput.val(''); 
    dataHolder.attr('data-action', 'add');

    }    
     productQuantityInput.attr('id', newQuantityInputId);
  
    dataHolder.attr('data-id', newProductId);
    dataHolder.attr('data-key', dataKey);
    


});
</script>

