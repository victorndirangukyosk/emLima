<?php echo $header; ?>
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="my-order-view-dashboard">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="back-link-block"><a href="<?php echo $continue; ?>"> <span class="back-arrow"><i class="fa fa-long-arrow-left"></i> </span> <?= $text_go_back ?></a></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="my-order-view-content">
                                    <div class="my-order">
                                        <div class="list-group my-order-group">
                                            <li class="list-group-item my-order-list-head">
                                                <h2 class="my-order-list-title"><?= $store_name?></h2>
                                                <span>
                                                    <?= $text_order_id_with_colon ?>
                                                    <span class="my-order-id-number">#<?php echo $order_id; ?></span>
                                                </span>
                                                <span class="my-order-id-item"><strong><?php echo $total_quantity; ?></strong> <?= $text_items ?></span>
                                            </li>
                                            <?php $i=0;  foreach ($products as $product) { ?>
                                                <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="mycart-product-img"><img src="<?= $product['image'] ?>" alt="" class="img-responsive"></div>
                                                    </div>
                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered) { ?>
                                                        <div class="col-md-4">
                                                    <?php } else { ?>
                                                        <div class="col-md-5">
                                                    <?php } ?>
                                                        <div class="mycart-product-info">
                                                            <h3> <?php echo $product['name']; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $product['unit']; ?></span>
                                                            </p>
                                                            <!-- <?php if($product['product_type'] == 'replacable') { ?>
                                                                <span class="badge badge-success replacable"   data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                                                                 <?= $text_replacable ?>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span  class="badge badge-danger replacable"  data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                                                    <?= $text_not_replacable ?>
                                                                </span>
                                                            <?php } ?> -->
                                                            
                                                            <?php foreach ($products_status as $product_status) { ?>
                                                                <?php if(trim($product['name']) == trim($product_status->product_name) && $product['unit'] == $product_status->unit ) { $is_true = false; ?>
                                                                   
                                                                    <?php if($product_status->status == 'Remaining') {  $is_true = true;?>
                                                                        <span class="badge badge-warning">
                                                                            <?= $text_remaining ?>
                                                                        </span>
                                                                    <?php } ?>

                                                                    <?php if($product_status->status == 'In-Transit') { $is_true = true; ?>
                                                                        <span class="badge badge-info">
                                                                            <?= $text_intransit ?>
                                                                        </span>
                                                                    <?php } ?>

                                                                    <?php if($product_status->status == 'Completed') { $is_true = true; ?>
                                                                        <span class="badge badge-success">
                                                                            <?= $text_completed ?>
                                                                        </span>
                                                                    <?php } ?>

                                                                    <?php if($product_status->status == 'Canceled') { $is_true = true; ?>
                                                                        <span class="badge badge-danger">
                                                                            <?= $text_cancelled ?>
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
                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered) { ?>
                                                        <div class="col-md-2">
                                                    <?php } else { ?>
                                                        <div class="col-md-3">
                                                    <?php } ?>
                                                        <div class="my-order-price">
                                                            <?php echo $product['quantity']; ?> x <?php echo $product['price']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="my-order-price">
                                                            <?php echo $product['total']; ?>
                                                        </div>
                                                    </div>

                                                    <?php if($this->config->get('config_account_return_product_status') == 'yes' && $delivered) { ?>
                                                        <div class="col-md-2">
                                                            <div class="my-order-price">
                                                                <a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>
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
                                                            <div class="checout-invoice-title"><img style="    height: 80px;width: 80px;" src="<?= $shopper_link.$delivery_data->driver->profile->drivers_photo ?>"></div>
                                                            <div class="checout-invoice-price"><?= $delivery_data->driver->first_name ?> <?= $delivery_data->driver->last_name ?>
                                                                <br>
                                                                <?= $delivery_data->driver->phone_number ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if(isset($delivery_data->status) && $delivery_data->status == 'delivered') { ?>

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
                                                            <input type="number" value="<?= $delivery_data->reviews->ratings?>" class="form-control" id="rating" name="rating">
                                                          </div>
                                                          <div class="form-group">
                                                            <label ><?= $text_review ?>:</label>

                                                            <?php if(isset($delivery_data->reviews) && isset($delivery_data->reviews->review)) { ?>
                                                                <textarea class="form-control" name="review" type="text" id="review"><?= $delivery_data->reviews->review ?> </textarea>
                                                            <?php } else {?>
                                                                <textarea class="form-control" name="review" type="text" id="review"></textarea>
                                                            <?php } ?>
                                                            
                                                          </div>
                                                            <div class="rating-success-message" style="color: green;">
                                                            </div>
                                                          <button type="button" class="btn btn-default" onclick="sendReview()"><?= $text_send ?></button>
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
    <?php echo $footer; ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
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
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
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
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
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
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

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

    <script type="text/javascript">
        function sendReview() {
            console.log("sendReview");
            console.log("timeslot");
            
           
            data = {
                rating :$('input[name="rating"]').val(),
                review :  $('textarea[name="review"]').val(),
                delivery_id: '<?= $delivery_id ?>'//"del_XPeEGFX3Hc4ZeWg5"
            }
            $('.rating-success-message').html('');

            $.ajax({
                url: 'index.php?path=checkout/success/sendOrderRating',
                type: 'post',
                data:data,
                dataType: 'json',
                success: function(response) {
                    console.log("sendReview");
                    console.log(response);
                    console.log("sendRevssiew");

                    $('.rating-success-message').html(response.message);

                    //$('#confirm-wrapper').html(html);
                }
            });

        }
    </script>
</html>
