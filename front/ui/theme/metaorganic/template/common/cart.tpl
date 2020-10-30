<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    <div class="mycart-header">
        <h4 class="modal-title" id="myModalLabel"><?= $text_my_cart ?>
        <span class="cart-header_items-count"> (<?= $this->cart->countProducts(); ?> <?= $text_item ?>)</span>
        <?php if($this->cart->countProducts() > 0) { ?>
        <span id="clearcart" class="cart-header_items-count clear-cart" data-confirm="<?= $text_comfirm_clear ?>" ><?= $button_clear_cart ?></span>
        <?php } ?>
        </h4>
    </div>  
    <!--<div class="store-shop-panel">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="store-logo-img"><img src="<?= $image ?>" alt="" class="img-responsive"></div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-8">
                <div class="store-mycart-info">
                    <p><span><?= $this->currency->format($this->cart->getTotal()) ?></span></p>
                </div>
            </div>
        </div>
    </div>-->
</div>
<div class="modal-body">
    <div class="mycart-product-listing">
    	<?php if (!$products) { ?>

    	<?php }else{ ?> 

    		<ul class="listnone">
            <?php $this->load->model('account/address'); ?>
                <?php foreach ($arr as $key=> $products) { ?>
                    <li> <b> <?php echo $this->model_account_address->getStoreNameById($key); ?> </b> <b style="float: right"> <?php echo $this->currency->format($this->cart->getTotalByStore($key)); ?>  </b> </li>

                    <li class="store_note<?= $key?> store-note"> <?php echo $this->model_account_address->getStoreTextById($key); ?> </li>

        			<?php foreach ($products as $product) { ?>

    		            <li id='action_remove_<?= $product["product_store_id"] ?>' >
    		                <div class="row">
    		                    <div class="col-md-4 col-sm-4 col-xs-4">
    		                    	<?php if ($product['thumb']) { ?>
    									 <div class="mycart-product-img"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" title="<?php echo $product['name']; ?>"></div>
    								<?php }?>  
                                </div>
                                
    		                    <div class="col-md-8 col-sm-8 nopl col-xs-8">
    		                        <div class="mycart-product-info">
                                       <a title="Remove item" class="delete-item" data-value='<?= $product["key"] ?>'  
                                           product_id='<?= $product["product_store_id"] ?>'  
                                           style=" background-color: #ec9f4e ;">  </a>

    		                            <h3 style="display: inline-block;"> <?php echo $product['name']; ?> </h3>
                                        <div style="font-size:13px;">
                                                <?php  $fpt ='';
                                                if(is_array($product['produce_type'])) {
                                                foreach ($product['produce_type'] as $pt) {
                                                    if($pt['type']!= null && $pt['type']!= 'null' )
                                                $fpt.=' '.  $pt['type'].'-'.$pt['value'] ;
                                                    }
                                                    }
                                                    ?><?= $fpt?></div> 
    		                            <p class="product-info">
                                            <span class="small-info"><?php echo ( isset($product['unit']) ? $product['unit'] : ''); ?></span>                        
                                        </p>

                                        <?php require 'action.tpl'; ?>

                                        <div class="product-items product-price">
                                                <div class="pro-qty-addbtn " data-variation-id="<?= $product['store_product_variation_id'] ?>" id="action_<?= $product['product_store_id'] ?>">
                                                </div>

                                                <div class="product-price-combo">
                                                     <?php if(isset($product['original_price'])) { ?>
                                                    <span class="price-cancelled"><?= $product['original_price'] ?></span>
                                                    <?php } else { ?>
                                                        <span class="price-cancelled"></span>
                                                    <?php } ?>
                                                    <?php if(isset($product['price'])) { ?>
                                                    <span class="price"><?= $product['price'] ?></span>
                                                    <?php } ?>
                                                </div>
                                                <span class="total-price"><?php echo $product['total']; ?></span>
                                        </div>
    		                        </div>
    		                    </div>
    		                </div>
    		            </li>
    		        <?php } ?>
                <?php } ?>
                <br/>
	        </ul>
	    <?php } ?>

    </div>
</div>
<script type="text/javascript">
     $(document).ready(function() {
        $('.replacable').on('click', function(){
            console.log("replacable");
            if($(this).attr('data-value') == 'replacable') {
                //toggle
                $(this).attr('data-value', 'not-replacable');
                $(this).removeClass('badge-success');
                $(this).addClass('badge-danger');
                $(this).html('<?= $text_not_replacable ?>');
                
                $(this).attr('title', '<?= $text_not_replacable_title ?>');
            } else {
                console.log("nss");
                $(this).attr('data-value', 'replacable');
                $(this).removeClass('badge-danger');
                $(this).addClass('badge-success');
                $(this).html('<?= $text_replacable ?>');
                $(this).attr('title', '<?= $text_replacable_title ?>');
            }   

            $product_type = $(this).attr('data-value');  

            $this = $(this);
            
            
            if($this.attr('data-key').length > 0) {
                console.log("replacable first"+$product_type);
                console.log($this.attr('data-key'));
                var response = cart.update_product_type($this.attr('data-key'),$product_type);
            }
        });
        return false;
    });


    

  $('.delete-item').on('click', function(){     
    
var key=$(this).attr('data-value');
 //alert(key); 

 $product_id=$(this).attr('product_id'); 
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
                           // Hide for qnty Box
				/*$qty_wrapper = $(document).find('#'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			 $qty_wrapper = $(document).find('.unique'+$product_store_id+'-'+$variation_id+' .middle-quantity').html($qty);
    			 $qty_wrapper = $(document).find('.unique_middle_button'+$product_store_id+'-'+$variation_id).html($qty);
				*/
                $('#flag-qty-id-'+$product_id+'-0').html('');
                $('#flag-qty-id-'+$product_id+'-0').css("display","none");
				 //reflact changes in list 
                $('#action_'+json['product_id']+'[data-variation-id="'+json['variation_id']+'"] .middle-quantity').html(json['quantity']);
                
				if (json['location'] == 'cart-checkout') {
					location = 'index.php?path=checkout/cart';
				} else {
                
                    //update total count for mobile 
                    $('.shoppingitem-fig').html(json['count_products']);
                        
					$('#cart').load('index.php?path=common/cart/info');

					$('.cart-panel-content').load('index.php?path=common/cart/newInfo');

					$('.cart-count').html(json['count_products']+' ITEMS IN CART ');
                    $('.cart-total-amount').html(json['total_amount']);
				}

				$.ajax({
			        url: 'index.php?path=common/home/cartDetails',
			        type: 'post',
			        dataType: 'json',

			        success: function(json) {
			            console.log(json);
                            alert('Item deleted successfully');


			            for (var key in json['store_note']) {
	                        //alert("User " + data[key] + " is #" + key); // "User john is #234"
	                        $('.store_note'+key).html(json['store_note'][key]);

	                        console.log(json['store_note'][key]);
	                    }

			            if (json['status']) {
			                console.log("yesz");
			                console.log(text);
			                $("#proceed_to_checkout").removeAttr("disabled");
			                $("#proceed_to_checkout").attr("href", json['href']);
			                //$("#proceed_to_checkout_button").html(json['text_proceed_to_checkout']);
			                //$('.checkout-modal-text').html(json['text_proceed_to_checkout']);

			                $("#proceed_to_checkout_button").css({ 'background-color' : '', 'border-color' : '' });
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

<style>

.delete-item:before {
    content: "\f014";
    font-family: FontAwesome;
    font-size: 22px;
    background-color: #ec9f4e !important;
    color: white;
}



.delete-item {
    background-color: #ec9f4e !important;
    background-image: none;
    color: #333;
    cursor: pointer;
    padding: 1px 10px ;
    cursor: pointer;
    text-decoration: none;    
    transition: all 0.3s linear;
    -moz-transition: all 0.3s linear;
    -webkit-transition: all 0.3s linear;
    border-radius: 999px;
    position: absolute;
    right: 0;
    top: 0;
    margin-right: 1rem;
}

.mycart-product-info  h3{
margin-top: .55rem;
}


.sp-quantity {
    width: 100%;
    display: flex;
    flex-flow: column nowrap;
    align-items: flex-end;
    justify-content: center;
    margin-top: 1.4rem;
}
</style>