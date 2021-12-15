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

        <script type="text/javascript">

            $(document).ready(function() {

            rating = '';
            console.log( < ? = $rating ? > + "rating received");
            if ( < ? = $rating ? > % 1 === 0) {
            rating = < ? = $rating ? > ;
            } else {
            rating = ( < ? = $rating ? > - .5) + "half";
            }
            console.log(rating);
            $('#star' + rating).removeAttr('disabled');
            $('#star' + rating).click();
            //driver rating start

            < ?php if (isset($delivery_data - > reviews) && isset($delivery_data - > reviews - > ratings)) { ? >
                    driver_rating = '';
            console.log( < ? = $delivery_data - > reviews - > ratings ? > + "driver_rating received");
            if ( < ? = $delivery_data - > reviews - > ratings ? > % 1 === 0) {
            driver_rating = < ? = $delivery_data - > reviews - > ratings ? > ;
            } else {
            driver_rating = ( < ? = $delivery_data - > reviews - > ratings ? > - .5) + "half";
            }
            console.log(driver_rating);
            $('#driver_star' + driver_rating).removeAttr('disabled');
            $('#driver_star' + driver_rating).click();
            < ?php } ? >
            });
            function sendReview() {
            console.log("sendReview");
            console.log("timeslot");
            data = {
            rating :$('#driver_rating').val(),
                    review :  '', //$('textarea[name="review"]').val(),
                    delivery_id: '<?= $delivery_id ?>'//"del_XPeEGFX3Hc4ZeWg5"
            }

            console.log(data);
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

            function saveOrderdriverRating(rating) {

            console.log(rating);
            console.log("saveOrderdriverRating");
            $('#driver_rating').val(rating);
            }

            function saveOrderRating(rating) {

            console.log(rating);
            console.log("saveOrderRating");
            data = {
            rating : rating,
                    //rating : 3.5,
                    order_id : < ? = $order_id ? >
            }
            //$('.rating-success-message').html('');

            console.log(data);
            $.ajax({
            url: 'index.php?path=checkout/success/saveOrderRating',
                    type: 'post',
                    data:data,
                    dataType: 'json',
                    success: function(response) {
                    console.log("saveOrderRating");
                    console.log(response);
                    }
            });
            }

            function return_product(return_product) {
            //function return_product() {

            //console.log(return_product);
            console.log("return_product");
            data = {
            order_id : < ? = $order_id ? >
            }

            console.log(data);
            $.ajax({
            url: 'index.php?path=account/order/can_return',
                    type: 'post',
                    data:data,
                    dataType: 'json',
                    success: function(response) {
                    console.log("saveOrderRating");
                    console.log(response);
                    if (response['can_return']) {
                    location = return_product;
                    } else {
                    alert("Sorry, Return Window has passed");
                    location = location;
                    }
                    }
            });
            }

            setInterval(function() {
            location = location;
            }, 60 * 1000); // 60 * 1000 milsec



        </script>
        <script>

            function checkProductSelected(){
            var len = $("input.select-item:checked:checked").length;
            if (len > 0){
            return true;
            } else{
            alert('Please select at least one product');
            return false;
            }
            }

            $(function(){

            //button select all or cancel
            /*$("#select-all").click(function () {
             var all = $("input.select-all")[0];
             all.checked = !all.checked
             var checked = all.checked;
             $("input.select-item").each(function (index,item) {
             item.checked = checked;
             });
             });
             
             //button select invert
             $("#select-invert").click(function () {
             $("input.select-item").each(function (index,item) {
             item.checked = !item.checked;
             });
             checkSelected();
             });
             
             //button get selected info
             $("#selected").click(function () {
             var items=[];
             $("input.select-item:checked:checked").each(function (index,item) {
             items[index] = item.value;
             });
             if (items.length < 1) {
             alert("no selected items!!!");
             }else {
             var values = items.join(',');
             console.log(values);
             var html = $("<div></div>");
             html.html("selected:"+values);
             html.appendTo("body");
             }
             });
             */

            //column checkbox select all or cancel
            $("input.select-all").click(function () {
            var checked = this.checked;
            $("input.select-item").each(function (index, item) {
            item.checked = checked;
            });
            });
            //check selected items
            $("input.select-item").click(function () {
            var checked = this.checked;
            console.log(checked);
            checkSelected();
            });
            //check is all selected
            function checkSelected() {
            var all = $("input.select-all")[0];
            var total = $("input.select-item").length;
            var len = $("input.select-item:checked:checked").length;
            console.log("total:" + total);
            console.log("len:" + len);
            all.checked = len === total;
            }

            });
        </script>
        </html>
        </form>
