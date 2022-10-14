<?php echo $header; ?>

<div class="page-container">
    <div class="session-controller">
        <div class="topspace"></div>
        <!--<div class="containerliquid whitebg test" style="min-height: 300px;">-->
            <div class="container">
                <div class="row">
                    
                    <br />
                    <br />
                    <br />
                    
                    <div class="aligntocenter col-md-12">
                        <h1 class="signupheading"><?php echo $heading_title; ?></h3>
                    </div>

                    <br />
                    <br />
                    <br />
                    <br />
                    
                    <div style="font-size: 16px;" class="col-md-10">
                        <?php echo html_entity_decode($text_message);  ?>
                    </div>
                    
                    <br />
                    <div style="font-size: 16px;" class="col-md-10">
                        <?php echo html_entity_decode($text_feedback_message);  ?>
                    </div>

                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />
                    
                    
                    <!--<br />
                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />-->
                    
                </div>
                
                <?php if(isset($total_products)) { ?>
                <!--ORDER SUMMARY-->
                <div class="row">
            <div class="col-md-12">
                <div class="my-order-view-dashboard">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="back-link-block"><span class="back-arrow"><h2 class="signupheading">Order Summary</h2></span></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="my-order-view-content">
                                <div class="my-order">
                                    <div class="list-group my-order-group">
                                        <li class="list-group-item my-order-list-head">

                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <h2 class="my-order-list-title"><?= $store_name?></h2>
                                                </div>
                                            </div>


                                            <span>
                                                <?= $text_order_id_with_colon ?>
                                                <span class="my-order-id-number"><?php echo "<b>".$order_id."</b>"; if(is_array($order_ids) && count($order_ids) > 0) { foreach($order_ids as $stid => $stoid){ echo '<b>'.' #'.$stoid.'</b>';  } } ?></span>
                                            </span>
                                            <span class="my-order-id-item"><strong><?php echo $total_products; ?></strong> <?= $text_products ?></span>
                                            <!--<span class="my-order-id-item"><strong><?php echo $total_quantity; ?></strong> <?= $text_products ?></span>-->
                                        </li>
                                        <?php $i=0;  foreach ($products as $product) { ?>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-2 col-xs-4">
                                                    <div class="mycart-product-img"><img src="<?= $product['image'] ?>" alt="" class="img-responsive"></div>
                                                </div>
                                                <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && $can_return) { ?>
                                                <div class="col-md-4 col-xs-8">
                                                    <?php } else { ?>
                                                    <div class="col-md-5 col-xs-8">
                                                        <?php } ?>
                                                        <div class="mycart-product-info">
                                                            <h3> <?php echo $product['name']; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $product['unit']; ?></span>
                                                            </p>

                                                            <?php foreach ($products_status as $product_status) { ?>
                                                            <?php if(trim($product['name']) == trim($product_status->product_name) && $product['unit'] == $product_status->unit ) { $is_true = false; ?>
                                                            <!--<span> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>-->
                                                            <?php if($product_status->status == 'Remaining') {  $is_true = true;?>
                                                            <span class="badge badge-warning">
                                                                <?= $text_remaining ?>
                                                            </span>
                                                            <?php } ?>


                                                            <?php if($product_status->status == 'not available') { $is_true = true; ?>
                                                            <span class="badge badge-danger">
                                                                <?= $text_not_avialable ?>
                                                            </span>
                                                            <?php } ?>

                                                            <?php if($product_status->status == 'picked') { $is_true = true; ?>
                                                            <span class="badge badge-success">
                                                                <?= $text_picked ?>
                                                            </span>
                                                            <?php } ?>

                                                            <?php if($product_status->status == 'replaced') { $is_true = true; ?>
                                                            <span class="badge badge-primary">
                                                                <?= $text_replaced ?>
                                                            </span>
                                                            <?php } ?>
                                                            <?php if(!$is_true) { ?>
                                                            <span class="badge badge-primary">
                                                                <?= $product_status->status ?>
                                                            </span>
                                                            <?php } ?>
                                                            <?php } } ?>
                                                        </div>
                                                    </div>
                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && $can_return) { ?>
                                                    <div class="col-md-2 col-xs-8">
                                                        <?php } else { ?>
                                                        <div class="col-md-3 col-xs-8">
                                                            <?php } ?>
                                                            <div class="my-order-price">
                                                                <?php echo $product['quantity']; ?> x <?php echo $product['price']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-xs-8">
                                                            <div class="my-order-price">
                                                                <?php echo $product['total']; ?>
                                                            </div>
                                                        </div>

                                                        <?php /* if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && is_null($product['return_id']) && $can_return) { ?>
                                                        <div class="col-md-2">
                                                            <?php /* ?>
                                                            <div class="my-order-price">
                                                                <!-- <a href="<?php echo $product['return']; ?>" id="return_button" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a> -->

                                                                <?php $url = $product['return']; ?>
                                                                <a onclick="return_product('<?= $url ?>')"  data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>

                                                                <!-- <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button> -->

                                                            </div>
                                                            <?php  ?>
                                                        </div>
                                                        <?php } */ ?>

                                                        <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && !is_null($product['return_id'])) { ?>
                                                        <div class="col-md-2 col-xs-8">
                                                            <div class="my-order-price">
                                                                Return Status: <?= $product['return_status'] ?>
                                                            </div>
                                                        </div>
                                                        <?php } ?>


                                                    </div>
                                                    </li>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="my-order-view-sidebar">
                                                <div class="checkout-sidebar">
                                                    <div class="checkout-total">

                                                        <?php foreach ($totals as $total) { ?>

                                                        <?php if($total['title'] == 'Total') { ?>
                                                        <?php if(count($totals) <= 2) { ?> 
                                                        <?php } ?>

                                                    </div>
                                                    <div class="checkout-payable">
                                                        <div class="checkout-payable-title"><?php echo $total['title']; ?></div>

                                                        <?php if(isset($settlement_amount) && $plain_settlement_amount != $subtotal) { ?>

                                                        <div class="checkout-payable-price"><?php echo $newTotal; ?> </div>
                                                        &nbsp;
                                                        <div class="checkout-payable-price" style="    text-decoration: line-through;color: red;    padding-right: 4px;"> <?php echo $total['text']; ?> </div> 


                                                        <?php } else {?>
                                                                <div class="checkout-payable-price"><?php echo $total['text']; ?></div>
                                                            <?php } ?>
                                                    </div>
                                                    <?php } elseif(strpos($total['title'], 'Delivery') !== false) { ?>
                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                                                        <div class="checout-invoice-price charges-free"><?php echo $total['text']; ?></div>
                                                    </div>


                                                    <?php } elseif(strpos($total['title'], 'Coupon') !== false) { ?>
                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?>

                                                        </div>

                                                        <?php if(strpos($total['text'], '0.00') !== false && !$coupon_cashback) { ?>
                                                        <div class="checkout-payable-price" style="color: red"><?= $cashbackAmount; ?> <sup>*</sup> </div>


                                                        <?php } elseif(strpos($total['text'], '0.00') !== false && $coupon_cashback) { ?>
                                                        <div class="checkout-payable-price" style="color: green"><?= $cashbackAmount; ?><sup>*</sup> </div>


                                                        <?php } else { ?>
                                                        <div class="checkout-payable-price"><?php echo $total['text']; ?></div>
                                                        <?php } ?>
                                                    </div>

                                                    <?php } else { ?>

                                                    <?php if($total['title'] == 'Sub-Total' && isset($settlement_amount) && $plain_settlement_amount != $subtotal) { ?>
                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                                                        <!-- <div class="checout-invoice-price"><?php echo $total['text']; ?></div> -->
                                                        <div class="checout-invoice-price"><?php echo $settlement_amount; ?> </div>
                                                        &nbsp;
                                                        <div class="checout-invoice-price" style="    text-decoration: line-through;color: red;    padding-right: 4px;"> <?php echo $total['text']; ?> </div>

                                                    </div>

                                                    <?php } else { ?>

                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                                                        <div class="checout-invoice-price"><?php echo $total['text']; ?></div>
                                                    </div>
                                                    <?php } ?>
                                                    <?php } }?>
                                                    <div class="checkout-invoice">
                                                        <div class="checkout-payable-price"><small>*<?= $cashback_condition; ?></small></div>
                                                    </div>
                                                </div>      
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--ORDER SUMMARY-->
                <?php } ?>
            </div>
         <!--</div>-->
    </div>
</div>
 
<?php echo $footer; ?>
<?= $feedback_modal ?>
<script type="text/javascript">



$( document ).ready(function() {
    <?php if(isset($load_feedback_popup)) { ?>
        $('#feedbackModal').modal('show');
    <?php }?>
});

<?php if(isset($redirect_url_return)) { ?>
    $(function(){

        console.log("Ce");
        setTimeout(function () {
            if('<?php echo !is_null($redirect_url) ?>') {

                //alert('index.php?path=account/order/info&order_id=' + <?= $order_id ?>);
                //console.log('<?= urlencode($redirect_url)?>');

                console.log("erf");
                console.log("<?= $redirect_url ?>");
                //location = 'index.php?path=account/order/info&order_id=' + <?= $order_id ?>;    
                location = '<?= $redirect_url ?>';    
                //location = '<?= $redirect_url ?>';    
            }
            
        }, 3000); 
    });

<?php } else { ?>

    //alert("er");
<?php } ?>
    
</script>