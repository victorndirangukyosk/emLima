<?php echo $header;?>
<div class="dashboard-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="my-order-view-dashboard">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alerter" style="display: none;">
                                <div class="alert alert-info normalalert">
                                    <p class="notice-text">Order updated successfully!</p>
                                </div>
                            </div>
                        </div>
                        <?php if($store_warning != NULL) { ?>
                        <div class="col-md-12">
                            <div class="alerter">
                                <div>
                                    <p class="notice-text"><?php echo $store_warning; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <div class="back-link-block"><a href="<?php echo $continue; ?>"> <span class="back-arrow"><i class="fa fa-long-arrow-left"></i> </span> <?= $text_go_back ?></a></div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-11">
                            <div class="back-link-block"><a href="<?php echo $continue; ?>"> <span class="back-arrow"><i class="fa fa-long-arrow-left"></i> </span> <?= $text_go_back ?></a></div>
                        </div>
                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && $can_return && ($returnProductCount>0)) { ?>
                            <div class="col-md-1">
                                                        
                               <?php $url = $product['return']; ?>
                               <a data-toggle="modal" data-target="#refundOrderModal" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>
                                              

                             </div>
                        <?php } ?>
                    </div> -->
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

                                                <?php if($show_rating) { ?>

                                                <?php if($take_rating) { ?>
                                                <div class="col-sm-4">
                                                    <span >
                                                        <fieldset class="rating">
                                                            <input type="radio" id="star5" name="rating" value="5" onclick="saveOrderRating(this.value);" />

                                                            <label class = "full" for="star5" title="Awesome - 5 stars"></label>


                                                            <input type="radio" id="star4half" name="rating" value="4.5" onclick="saveOrderRating(this.value);"  />

                                                            <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

                                                            <input type="radio" id="star4" name="rating" value="4" onclick="saveOrderRating(this.value);" />

                                                            <label class = "full" for="star4" title="Pretty good - 4 stars"></label>

                                                            <input type="radio" id="star3half" name="rating" value="3.5" onclick="saveOrderRating(this.value);" />

                                                            <label class="half" for="star3half" title="Meh - 3.5 stars"></label>

                                                            <input type="radio" id="star3" name="rating" value="3" onclick="saveOrderRating(this.value);" />

                                                            <label class = "full" for="star3" title="Meh - 3 stars"></label>

                                                            <input type="radio" id="star2half" name="rating" value="2.5" onclick="saveOrderRating(this.value);" />

                                                            <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

                                                            <input type="radio" id="star2" name="rating" value="2" onclick="saveOrderRating(this.value);" />

                                                            <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>


                                                            <input type="radio" id="star1half" name="rating" value="1.5" onclick="saveOrderRating(this.value);"  />

                                                            <label class="half" for="star1half" title="Meh - 1.5 stars"></label>


                                                            <input type="radio" id="star1" name="rating" value="1" onclick="saveOrderRating(this.value);" />

                                                            <label class = "full" for="star1" title="Sucks big time - 1 star"></label>


                                                            <input type="radio" id="starhalf" name="rating" value=".5" onclick="saveOrderRating(this.value);" />

                                                            <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                                                        </fieldset>
                                                    </span>
                                                </div>
                                                <?php } else { ?>
                                                <div class="col-sm-4">
                                                    <span >
                                                        <fieldset class="show_rating">
                                                            <input type="radio" id="star5" name="show_rating" value="5" disabled="disabled" />

                                                            <label class = "full" for="star5" title="Awesome - 5 stars"></label>


                                                            <input type="radio" id="star4half" name="show_rating" value="4.5" disabled="disabled"  />

                                                            <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>

                                                            <input type="radio" id="star4" name="show_rating" value="4" disabled="disabled" />

                                                            <label class = "full" for="star4" title="Pretty good - 4 stars"></label>

                                                            <input type="radio" id="star3half" name="show_rating" value="3.5" disabled="disabled" />

                                                            <label class="half" for="star3half" title="Meh - 3.5 stars"></label>

                                                            <input type="radio" id="star3" name="show_rating" value="3" disabled="disabled" />

                                                            <label class = "full" for="star3" title="Meh - 3 stars"></label>

                                                            <input type="radio" id="star2half" name="show_rating" value="2.5" disabled="disabled" />

                                                            <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>

                                                            <input type="radio" id="star2" name="show_rating" value="2" disabled="disabled" />

                                                            <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>


                                                            <input type="radio" id="star1half" name="show_rating" value="1.5" disabled="disabled"  />

                                                            <label class="half" for="star1half" title="Meh - 1.5 stars"></label>


                                                            <input type="radio" id="star1" name="show_rating" value="1" disabled="disabled" />

                                                            <label class = "full" for="star1" title="Sucks big time - 1 star"></label>


                                                            <input type="radio" id="starhalf" name="show_rating" value=".5" disabled="disabled" />

                                                            <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                                                        </fieldset>
                                                    </span>
                                                </div>
                                                <?php } ?>

                                                <?php } ?>

                                            </div>


                                            <span>
                                                <?= $text_order_id_with_colon ?>
                                                <span class="my-order-id-number">#<?php echo $order_id; ?></span>
                                                <input type="hidden" id="edit_order_id" name="edit_order_id" value="<?php echo $order_id; ?>"/>
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
                                                    <div class="col-md-3 col-xs-8">
                                                        <?php } ?>
                                                        <div class="mycart-product-info">
                                                            <h3> <?php echo $product['name']; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $product['unit']; ?></span>
                                                            </p>
                                                            <!--<?php if($product['product_type'] == 'replacable') { ?>
                                                                <span class="badge badge-success replacable"   data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                                                                 <?= $text_replacable ?>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span  class="badge badge-danger replacable"  data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                                                    <?= $text_not_replacable ?>
                                                                </span>
                                                            <?php } ?>-->

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
                                                            <!--<div class="my-order-price">
                                                                <?php echo $product['quantity']; ?> x <?php echo $product['price']; ?>
                                                            </div>-->
                                                            <div class="my-order-price" id="<?php echo $product['product_id'] ?>">           
                                                                <input type="button" class="sp-minus fff mini-minus-quantitys ddd" data-id="<?php echo $product['product_id'] ?>" data-unit="<?php echo $product['unit'] ?>" data-orderid="<?php echo $order_id; ?>" id="minus" value="-" <?php if($order_status_id != 15 && $order_status_id != 14) { ?> disabled="" <?php } ?>>
                                                                       <span class="sp-input middle-quantity quntity-input product-count" id="<?php echo 'span'.$product['product_id'] ?>" style="width:50px;">
                                                                    <?php if($product['unit'] == 'Kg' || $product['unit'] == 'Kgs' ) { echo  number_format($product['quantity'], 2); } else { echo round($product['quantity'], 0); } ?>        </span>

                                                                <input type="button" class="sp-plus fff mini-plus-quantitys ddd" data-id="<?php echo $product['product_id'] ?>" data-unit="<?php echo $product['unit'] ?>" data-orderid="<?php echo $order_id; ?>" id="plus" value="+" <?php if($order_status_id != 15 && $order_status_id != 14) { ?> disabled="" <?php } ?>>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-8">
                                                            <div class="my-order-price" id="producttotal<?php echo $product['product_id'] ?>">
                                                                <?php echo $product['total']; ?>
                                                            </div>
                                                        </div>

                                                        <?php /* if($this->config->get('config_account_return_product_status') == 'yes' && $delivered && is_null($product['return_id']) && $can_return) { ?>
                                                        <div class="col-md-3">
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
                                                        <div class="col-md-3 col-xs-8">
                                                            <div class="my-order-price">
                                                                Return Status: <?= $product['return_status'] ?>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="col-md-1 col-xs-8">
                                                            <a title="Remove item" class="button remove-item" style="background-color:#ec9f4e;" data-unit="<?php echo $product['unit']; ?>" data-id="<?php echo $product['product_id']; ?>" data-orderid="<?php echo $order_id; ?>" value="0" id="remove_item"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                        </div>

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
                                                                <div class="checkout-payable-price" id="total<?php echo $order_id; ?>"><?php echo $total['text']; ?></div>
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

                                                    <?php } elseif($total['title'] == 'Sub-Total') { ?>

                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                                                        <div class="checout-invoice-price" id="subtotal<?php echo $order_id; ?>"><?php echo $total['text']; ?></div>
                                                    </div>
                                                    <?php } elseif($total['title'] == 'VAT16') { ?>
                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                                                        <div class="checout-invoice-price" id="vat<?php echo $order_id; ?>"><?php echo $total['text']; ?></div>
                                                    </div>
                                                    <?php } else { ?>
                                                    <div class="checkout-invoice">
                                                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                                                        <div class="checout-invoice-price" id="subtotal<?php echo $order_id; ?>"><?php echo $total['text']; ?></div>
                                                    </div>
                                                    <?php } } }  ?>
                                                    <div class="checkout-invoice">
                                                        <div class="checkout-payable-price"><small>*<?= $cashback_condition; ?></small></div>
                                                    </div>
                                                </div>      

                                                <!--<div class="checkout-sidebar-merchant-box-old">
                                                    <li class="list-group-item my-order-list-head"><center><h2 class="my-order-list-title">Actions</h2></center></li>
                                                    <div class="checkout-sidebar">
                                                        <input type="hidden" value="<?php echo $order_status_id; ?>" id="order_status_number" name="order_status_number">
                                                        <?php if($order_status_id == 15) { ?>
                                                        <div class="row" style="margin-bottom: 8px">
                                                            <div class="col-md-12" id="approve_order_div">
                                                                <button id="approve_order" data-id="<?php echo $order_id; ?>" data-custid="<?php echo $order_customer_id; ?>" data-logcustid="<?php echo $loogged_customer_id; ?>" class="btn btn-primary" type="button">APPROVE ORDER</button>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-bottom: 8px">
                                                            <div class="col-md-12">
                                                                <button id="reject_order" data-id="<?php echo $order_id; ?>" data-custid="<?php echo $order_customer_id; ?>" data-logcustid="<?php echo $loogged_customer_id; ?>" class="btn btn-primary" type="button">REJECT ORDER</button>
                                                            </div>
                                                        </div>
                                                        <?php }  else { ?>
                                                        <div class="row" style="margin-bottom: 8px">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-primary" disabled=""><?php echo $order_status_name; ?></button>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>-->

                                            </div>

                                        </div>
                                    </div>
                                    <?php if(htmlspecialchars($_GET["order_status"])=='Delivered') { ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="my-order-view-sidebar">
                                                <li class="list-group-item my-order-list-head">
                                                    <h2 class="my-order-list-title">
                                                        <?= $text_delivery_detail ?>
                                                        <?php if(count($delivery_data) > 0) { ?>
                                                        # <?= $delivery_data->delivery_id ?>
                                                        <?php } ?>
                                                    </h2>
                                                </li>
                                                <div class="checkout-sidebar">
                                                    <div class="">

                                                        <?php if(!isset($delivery_data->assigned_to)) { ?>
                                                        <center> <?= $text_no_delivery_alloted ?></center>

                                                        <?php } elseif(isset($delivery_data->assigned_to)) { ?>
                                                        <div class="checkout-invoice">
                                                            <div class="checout-invoice-title"><a href="<?= $shopper_link.$delivery_data->driver->profile->drivers_photo ?>" target="_blank" > <img style="    height: 80px;width: 80px;" src="<?= $shopper_link.$delivery_data->driver->profile->drivers_photo ?>"> </a></div>
                                                            <div class="checout-invoice-price"><?= $delivery_data->driver->first_name ?> <?= $delivery_data->driver->last_name ?>
                                                                <br>
                                                                <?= $delivery_data->driver->phone_number ?>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>

                                                    <!-- new start -->


                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="my-order-view-sidebar">

                                                                <form method="post" action="" id="review-form">
                                                                    <div class="form-group">


                                                                        <input type="hidden" value="<?= $delivery_data->reviews->ratings?>" class="form-control" id="driver_rating" name="rating">

                                                                        <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->ratings)) { ?>
                                                                        <div class="col-sm-12">
                                                                            <span >
                                                                                <fieldset class="show_driver_rating">
                                                                                    <input type="radio" id="driver_star5" name="show_driver_rating" value="5" disabled="disabled" />

                                                                                    <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                                    <input type="radio" id="driver_star4half" name="show_driver_rating" value="4.5" disabled="disabled"  />

                                                                                    <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star4" name="show_driver_rating" value="4" disabled="disabled" />

                                                                                    <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star3half" name="show_driver_rating" value="3.5" disabled="disabled" />

                                                                                    <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star3" name="show_driver_rating" value="3" disabled="disabled" />

                                                                                    <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star2half" name="show_driver_rating" value="2.5" disabled="disabled" />

                                                                                    <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star2" name="show_driver_rating" value="2" disabled="disabled" />

                                                                                    <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                                    <input type="radio" id="driver_star1half" name="show_driver_rating" value="1.5" disabled="disabled"  />

                                                                                    <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                                    <input type="radio" id="driver_star1" name="show_driver_rating" value="1" disabled="disabled" />

                                                                                    <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                                    <input type="radio" id="driver_starhalf" name="show_driver_rating" value=".5" disabled="disabled" />

                                                                                    <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                                </fieldset>
                                                                            </span>
                                                                        </div>

                                                                        <?php } else { ?>


                                                                        <div class="col-sm-12">
                                                                            <span >
                                                                                <fieldset class="driver_rating">
                                                                                    <input type="radio" id="driver_star5" name="driver_rating" value="5" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                                    <input type="radio" id="driver_star4half" name="driver_rating" value="4.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                                    <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star4" name="driver_rating" value="4" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star3half" name="driver_rating" value="3.5" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star3" name="driver_rating" value="3" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star2half" name="driver_rating" value="2.5" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                                    <input type="radio" id="driver_star2" name="driver_rating" value="2" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                                    <input type="radio" id="driver_star1half" name="driver_rating" value="1.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                                    <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                                    <input type="radio" id="driver_star1" name="driver_rating" value="1" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                                    <input type="radio" id="driver_starhalf" name="driver_rating" value=".5" onclick="saveOrderdriverRating(this.value);" />

                                                                                    <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                                </fieldset>
                                                                            </span>
                                                                        </div>

                                                                        <?php } ?>
                                                                    </div>

                                                                    <div class="rating-success-message" style="color: green;">
                                                                    </div>

                                                                    <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>

                                                                    <button type="button" class="btn btn-default disabled"><?= $text_send ?></button>

                                                                    <?php } else { ?>

                                                                    <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button>


                                                                    <?php } ?>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- end -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <?php if(isset($delivery_data->status) && $delivery_data->status == 441 && false) { ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="my-order-view-sidebar">
                                                <li class="list-group-item my-order-list-head">
                                                    <h2 class="my-order-list-title">
                                                        <?= $text_send_rating ?>
                                                    </h2>
                                                </li>
                                                <div class="checkout-sidebar">
                                                    <div class="">
                                                        <form method="post" action="" id="review-form">
                                                            <div class="form-group">
                                                                <label ><?= $text_rating ?>: </label>

                                                                <input type="hidden" value="<?= $delivery_data->reviews->ratings?>" class="form-control" id="driver_rating" name="rating">

                                                                <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->ratings)) { ?>
                                                                <div class="col-sm-12">
                                                                    <span >
                                                                        <fieldset class="show_driver_rating">
                                                                            <input type="radio" id="driver_star5" name="show_driver_rating" value="5" disabled="disabled" />

                                                                            <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star4half" name="show_driver_rating" value="4.5" disabled="disabled"  />

                                                                            <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star4" name="show_driver_rating" value="4" disabled="disabled" />

                                                                            <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3half" name="show_driver_rating" value="3.5" disabled="disabled" />

                                                                            <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3" name="show_driver_rating" value="3" disabled="disabled" />

                                                                            <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2half" name="show_driver_rating" value="2.5" disabled="disabled" />

                                                                            <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2" name="show_driver_rating" value="2" disabled="disabled" />

                                                                            <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1half" name="show_driver_rating" value="1.5" disabled="disabled"  />

                                                                            <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1" name="show_driver_rating" value="1" disabled="disabled" />

                                                                            <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                            <input type="radio" id="driver_starhalf" name="show_driver_rating" value=".5" disabled="disabled" />

                                                                            <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                        </fieldset>
                                                                    </span>
                                                                </div>

                                                                <?php } else { ?>


                                                                <div class="col-sm-12">
                                                                    <span >
                                                                        <fieldset class="driver_rating">
                                                                            <input type="radio" id="driver_star5" name="driver_rating" value="5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star5" title="Awesome - 5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star4half" name="driver_rating" value="4.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                            <label class="half" for="driver_star4half" title="Pretty good - 4.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star4" name="driver_rating" value="4" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star4" title="Pretty good - 4 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3half" name="driver_rating" value="3.5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class="half" for="driver_star3half" title="Meh - 3.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star3" name="driver_rating" value="3" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star3" title="Meh - 3 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2half" name="driver_rating" value="2.5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class="half" for="driver_star2half" title="Kinda bad - 2.5 driver_stars"></label>

                                                                            <input type="radio" id="driver_star2" name="driver_rating" value="2" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star2" title="Kinda bad - 2 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1half" name="driver_rating" value="1.5" onclick="saveOrderdriverRating(this.value);"  />

                                                                            <label class="half" for="driver_star1half" title="Meh - 1.5 driver_stars"></label>


                                                                            <input type="radio" id="driver_star1" name="driver_rating" value="1" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class = "full" for="driver_star1" title="Sucks big time - 1 driver_star"></label>


                                                                            <input type="radio" id="driver_starhalf" name="driver_rating" value=".5" onclick="saveOrderdriverRating(this.value);" />

                                                                            <label class="half" for="driver_starhalf" title="Sucks big time - 0.5 driver_stars"></label>
                                                                        </fieldset>
                                                                    </span>
                                                                </div>

                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group">
                                                                <label ><?= $text_review ?>:</label>

                                                                <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>
                                                                <textarea class="form-control" name="review" type="text" id="review" disabled><?= $delivery_data->reviews->review ?> </textarea>
                                                                <?php } else {?>
                                                                <textarea class="form-control" name="review" type="text" id="review"></textarea>
                                                            <?php } ?>

                                                            </div>
                                                            <div class="rating-success-message" style="color: green;">
                                                            </div>

                                                            <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>

                                                            <button type="button" class="btn btn-default disabled"><?= $text_send ?></button>

                                                            <?php } else { ?>

                                                            <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button>


                                                            <?php } ?>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</div>
<div class="modal-wrapper"></div>                                                            

        <div class="refundOrderModal-popup">
            <div class="modal fade" id="refundOrderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <div class="store-head">
                                <h1>Return Initiation</h1>
                                <h4>Please Fill details to initate return:</h4>
                                <br>
                                <form id="refund-form" onsubmit="return checkProductSelected()" action="<?= $action ?>" autocomplete="off" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12" style="padding-right: 15px;padding-left: 15px;">
                                                <input type="hidden" name="order_id" value="<?php echo $this->request->get['order_id'];?>">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th>Return Qunatity</th>
                                                            <th>Select All <input type="checkbox" class="select-all checkbox" name="select-all" /></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($products as $product) {
                                                        if(is_null($product['return_id'])){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $product['name'];?></td>
                                                            <td><input type="text" name="return_qty[]" value="<?php echo $product['quantity']?>"></td>
                                                            <td><input type="checkbox" class="select-item checkbox" name="select-products[]" value="<?php echo $product['product_id']?>" /></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label class="col-sm-4 control-label"><?php echo $entry_reason; ?></label>
                                            <div class="col-sm-6">
                                                <?php foreach ($return_reasons as $return_reason) { ?>
                                                <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="return_reason_id" required value="<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
                                                        <?php echo $return_reason['name']; ?></label>
                                                </div>
                                                <?php } else { ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="return_reason_id"  required value="<?php echo $return_reason['return_reason_id']; ?>" />
                                                        <?php echo $return_reason['name']; ?></label>
                                                </div>
                                                <?php  } ?>
                                                <?php  } ?>
                                                <?php if ($error_reason) { ?>
                                                <div class="text-danger"><?php echo $error_reason; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label class="col-sm-4 control-label"><?php echo $entry_return_action; ?></label>
                                            <div class="col-sm-6">
                                                <?php /*foreach ($return_actions as $return_action) { ?>
                                                <?php if ($return_action['return_action_id'] == $return_action_id) { ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="return_action_id" required value="<?php echo $return_action['return_action_id']; ?>" checked="checked" />
                                                        <?php echo $return_action['name']; ?></label>
                                                </div>
                                                <?php } else { ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="return_action_id" required  value="<?php echo $return_action['return_action_id']; ?>" />
                                                        <?php echo $return_action['name']; ?></label>
                                                </div>
                                                <?php  } ?>
                                                <?php  } ?>
                                                <?php if ($error_return_action) { ?>
                                                <div class="text-danger"><?php echo $error_return_action; ?></div>
                                                <?php } */ ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="customer_desired_action" required value="refund" />
                                                        Refund Money</label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="customer_desired_action" required value="replace" />
                                                        Replace Product</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group required">
                                            <label class="col-sm-4 control-label"><?php echo $entry_opened; ?></label>
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    <?php if ($opened) { ?>
                                                    <input type="radio" name="opened" value="1" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="radio" name="opened" value="1" />
                                                    <?php } ?>
                                                    <?php echo $text_yes; ?></label>
                                                <label class="radio-inline">
                                                    <?php if (!$opened) { ?>
                                                    <input type="radio" name="opened" value="0" checked="checked" />
                                                    <?php } else { ?>
                                                    <input type="radio" name="opened" value="0" />
                                                    <?php } ?>
                                                    <?php echo $text_no; ?></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="input-comment"><?php echo $entry_fault_detail; ?></label>
                                            <div class="col-sm-4">
                                            </div>
                                            <div class="col-sm-8">
                                                <textarea name="comment" rows="10" placeholder="<?php echo $entry_fault_detail; ?>" id="input-comment" class="form-control input-lg"><?php echo $comment; ?></textarea>
                                            </div>
                                        </div>
                                        <?php if ($text_agree) { ?>
                                        <div class="buttons clearfix" style="margin-bottom: 20px;">
                                            <div class="col-md-12">
                                                <?php echo $text_agree; ?>
                                                <input type="checkbox" required name="agree" value="1"/>
                                                <input type="submit"  value="<?php echo $button_submit; ?>" class="btn-orange btn btn-primary" />
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="buttons clearfix" style="margin-bottom: 20px;">
                                            <div class="col-md-6">
                                                <input type="submit"  value="<?php echo $button_submit; ?>" class="btn-orange btn btn-primary" />
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $footer; ?>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

        <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
        <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
        </body>

        <?php if ($kondutoStatus) { ?>
        <script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

        <script type="text/javascript">
                                    var __kdt = __kdt || [];
                                    var public_key = '<?php echo $konduto_public_key ?>';
                                    console.log("public_key");
                                    console.log(public_key);
                                    __kdt.push({"public_key": public_key}); // The public key identifies your store
                                    __kdt.push({"post_on_load": false});
                                    (function() {
                                    var kdt = document.createElement('script');
                                    kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
                                    kdt.async = true; kdt.src = 'https://i.k-analytix.com/k.js';
                                    var s = document.getElementsByTagName('body')[0];
                                    console.log(s);
                                    s.parentNode.insertBefore(kdt, s);
                                    })();
                                    var visitorID;
                                    (function() {
                                    var period = 300;
                                    var limit = 20 * 1e3;
                                    var nTry = 0;
                                    var intervalID = setInterval(function() {
                                    var clear = limit / period <= ++nTry;
                                    console.log("visitorID trssy");
                                    if (typeof (Konduto.getVisitorID) !== "undefined") {
                                    visitorID = window.Konduto.getVisitorID();
                                    clear = true;
                                    }
                                    console.log("visitorID clear");
                                    if (clear) {
                                    clearInterval(intervalID);
                                    }
                                    }, period);
                                    })(visitorID);
                                    var page_category = 'order-detail-page';
                                    (function() {
                                    var period = 300;
                                    var limit = 20 * 1e3;
                                    var nTry = 0;
                                    var intervalID = setInterval(function() {
                                    var clear = limit / period <= ++nTry;
                                    if (typeof (Konduto.sendEvent) !== "undefined") {

                                    Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                                    clear = true;
                                    }
                                    if (clear) {
                                    clearInterval(intervalID);
                                    }
                                    },
                                            period);
                                    })(page_category);
        </script>

        <?php } ?>
        <script>
        $(document).ready(function () {
        $(document).delegate('.open-popup', 'click', function () {
            $('.search-dropdown').css('display', 'none');
       $('.search-dropdown').find('li').remove();
        $('.open-popup').prop('disabled', true);
        // console.log("product blocks" + $(this).attr('data-id'));
        $.get('index.php?path=product/product/order_edit_view&product_store_id=' + $(this).attr('data-id') + '&store_id=' + $(this).attr('data-store')+ '&edit_order_id=' + $('#edit_order_id').val(), function (data) {
        $('.open-popup').prop('disabled', false);
        $('.modal-wrapper').html(data);
        $('#popupmodal').modal('show');
        });
        $('#product_name').val('');
        });
        });
        $(document).delegate('#plus, #minus', 'click', function (e) {
            //alert('in progress');
            //return false;
            e.preventDefault();
            var product_id = $(this).attr('data-id');
            var order_id = $(this).attr('data-orderid');
            console.log($(this).attr('data-id'));
            console.log($(this).attr('id'));
            console.log($(this).attr('data-unit'));
            console.log($("#" + $(this).attr('data-id')).text().replace(/\s/g, ''));
            console.log($(this).attr('data-orderid'));
            var quantity = $("#" + $(this).attr('data-id')).text().replace(/\s/g, '');
            if ($(this).attr('id') == 'minus') {
            if ($(this).attr('data-unit') == 'Kg' || $(this).attr('data-unit') == 'Kgs')
            {
            var qty = parseFloat(quantity) - 0.5;
            console.log(qty);
            } else {
            var qty = parseFloat(quantity) - 1;
            console.log(qty);
            }
            }

            if ($(this).attr('id') == 'plus') {
            if ($(this).attr('data-unit') == 'Kg' || $(this).attr('data-unit') == 'Kgs')
            {
            var qty = parseFloat(quantity) + 0.5;
            console.log(qty);
            } else {
            var qty = parseFloat(quantity) + 1;
            console.log(qty);
            }
            }
            if (qty < 0) {
            alert('Invalid Quantity!');
            return false;
            }
            $.ajax({
            url: 'index.php?path=account/order/edit_full_order',
                    type: 'post',
                    data: { order_id: order_id, product_id: product_id, quantity: qty, unit: $(this).attr('data-unit')},
                    dataType: 'json',
                    beforeSend: function () {
                    $("#minus").prop('disabled', true);
                    $("#plus").prop('disabled', true); 
                    //$('#cart > button').button('loading');
                    },
                    complete: function () {
                    $("#minus").prop('disabled', false);
                    $("#plus").prop('disabled', false);
                    //$('#cart > button').button('reset');
                    },
                    success: function (json) {
                    if (json.status = true) {
                    $("#span" + product_id).text(qty);
                    $("#producttotal" + product_id).text(json.product_total_price);
                    $("#subtotal" + order_id).text(json.sub_total_amount);
                    $("#subtotal" + order_id).text(json.sub_total_amount);
                    $("#vat" + order_id).text(json.total_tax_amount);
                    $("#total" + order_id).text(json.total_amount);
                    $(".alerter").show();
                    $('.alerter').delay(5000).fadeOut('slow');
                    if(qty <= 0) {
                    var delay = 5000;
                    setTimeout(function(){ location.reload(); }, delay);    
                    }
                    if(json.hasOwnProperty('redirect') && json.redirect != '') {
                    var delay = 5000;
                    setTimeout(function(){ window.location = json.redirect; }, delay);    
                    }
                    } else {
                    alert('Please try again later!');
                    return false;
                    }
                    console.log(json);
                    }
            });
            });
        $(document).delegate('#remove_item', 'click', function (e) { 
            e.preventDefault();
            var product_id = $(this).attr('data-id');
            var order_id = $(this).attr('data-orderid');
            console.log($(this).attr('data-id'));
            console.log($(this).attr('id'));
            console.log($(this).attr('data-unit'));
            console.log($(this).attr('data-orderid'));
            var quantity = 0;
            if (quantity < 0) {
            alert('Invalid Quantity!');
            return false;
            }
            $.ajax({
            url: 'index.php?path=account/order/edit_full_order',
                    type: 'post',
                    data: { order_id: order_id, product_id: product_id, quantity: quantity, unit: $(this).attr('data-unit')},
                    dataType: 'json',
                    beforeSend: function () {
                    $("#minus").prop('disabled', true);
                    $("#plus").prop('disabled', true); 
                    $("#remove_item").prop('disabled', true); 
                    //$('#cart > button').button('loading');
                    },
                    complete: function () {
                    $("#minus").prop('disabled', false);
                    $("#plus").prop('disabled', false);
                    $("#remove_item").prop('disabled', false); 
                    //$('#cart > button').button('reset');
                    },
                    success: function (json) {
                    if (json.status = true) {
                    $("#span" + product_id).text(quantity);
                    $("#producttotal" + product_id).text(json.product_total_price);
                    $("#subtotal" + order_id).text(json.sub_total_amount);
                    $("#subtotal" + order_id).text(json.sub_total_amount);
                    $("#vat" + order_id).text(json.total_tax_amount);
                    $("#total" + order_id).text(json.total_amount);
                    $(".alerter").show();
                    $('.alerter').delay(5000).fadeOut('slow');
                    if(json.hasOwnProperty('redirect') && json.redirect != '') {
                    var delay = 5000;
                    setTimeout(function(){ window.location = json.redirect; }, delay);    
                    } else {
                    var delay = 5000;
                    setTimeout(function(){ location.reload(); }, delay);
                    }
                    } else {
                    alert('Please try again later!');
                    return false;
                    }
                    console.log(json);
                    }
            });
        });
        </script>
        <style>
    a.remove-item {
    margin-top: 15px;
    background-color: #ec9f4e !important;
    background-image: none;
    color: #333;
    cursor: pointer;
    padding: 8px 13px;
    cursor: pointer;
    text-decoration: none;
    float: left;
    transition: all 0.3s linear;
    -moz-transition: all 0.3s linear;
    -webkit-transition: all 0.3s linear;
    border: 1px #ddd solid;
    border-radius: 999px;  
    }
        </style>
        </html>
