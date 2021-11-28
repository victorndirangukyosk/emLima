<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    <div class="mycart-header">
        <h4 class="modal-title" id="myModalLabel"><?= $text_my_cart ?>
        <span class="cart-header_items-count"> (<?= $this->cart->countProducts(); ?> <?= $text_item ?>)</span>
        <span id="clearcart" class="cart-header_items-count clear-cart" data-confirm="<?= $text_comfirm_clear ?>" ><?= $button_clear_cart ?></span>
         </h4>
    </div>  
    <div class="store-shop-panel">
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
    </div>
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
    		                            <h3> <?php echo $product['name']; ?> </h3>
    		                            <p class="product-info">

                                            <span class="small-info"><?php echo ( isset($product['unit']) ? $product['unit'] : ''); ?></span>

                                            <!-- <?php if($product['product_type'] == 'replacable') { ?>
                                                <span   class="badge badge-success replacable" style="cursor: pointer;" data-key='<?= $product["key"] ?>' data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                                                 <?= $text_replacable ?>
                                                </span>
                                            <?php } else { ?>
                                                <span  class="badge badge-danger replacable" style="cursor: pointer;" data-key='<?= $product["key"] ?>' data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                                    <?= $text_not_replacable ?>
                                                </span>
                                            <?php } ?>  -->
                                            
                                        </p>
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

                                        <?php require 'action.tpl'; ?>
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
</script>
