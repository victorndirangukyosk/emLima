<form id="edit-address-form">
 
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="my-order-view-dashboard">                    
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="my-order-view-content">
                                <div class="my-order">
                                    <div class="list-group my-order-group">
                                        <li class="list-group-item my-order-list-head">

                                            <div class="row">
                                                <div class="col-sm-4" align="left">
                                                    <h2 class="my-order-list-title">#<?php echo $order_id; ?></h2>
                                                </div>
                                                <div class="col-sm-4">
                                                    <h2 class="my-order-list-title">Store Name: <?= $store_name?></h2>
                                                </div>
                                                <div class="col-sm-4" align="right">
                                                    <h2 class="my-order-list-title"><span class="my-order-id-item"><strong><?php echo $total_products; ?></strong> <?= $text_products ?></span></h2>
                                                </div>
                                            </div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
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
        </html>
        </form>
