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
                                                <div class="col-sm-2" align="left">
                                                    <h2 class="my-order-list-title">#<?php echo $order_id; ?></h2>
                                                </div>
                                                <div class="col-sm-6" align="center">
                                                    <h2 class="my-order-list-title"><?= $store_name?></h2>
                                                </div>
                                                <div class="col-sm-4" align="right">
                                                    <h2 class="my-order-list-title"><span class="my-order-id-item"><strong><?php echo $total_products; ?></strong> <?= $text_products ?></span></h2>
                                                </div>
                                            </div>
                                        </li>
                                        <?php $i=0;  foreach ($products as $product) { ?>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-3 col-xs-8">
                                                 <div class="my-order-price">
                                                    <h3> <?php echo $product['name']; ?> <?php echo "(". $product['unit'] . ")"; ?></h3>
                                                 </div>   
                                                </div>
                                                <div class="col-md-3 col-xs-8" align="right">
                                                    <div class="my-order-price">
                                                        <input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id; ?>">
                                                        <select class="input-cart" style="border-color:#767676 !important;" id="issue_type[<?= $product['product_id'] ?>]" name="issue_type[<?= $product['product_id'] ?>]" >
                                                            <option value="Missed">Missed Product</option>
                                                            <option value="Rejected">Rejected Product</option>
                                                        </select> 
                                                    </div> 
                                                </div>
                                                <div class="col-md-1 col-xs-8">
                                                 <div class="my-order-price">
                                                     <input type="number" class="input-cart-qty" value="" placeholder="Qty" id="qty[<?= $product['product_id'] ?>]" name="qty[<?= $product['product_id'] ?>]">
                                                 </div>   
                                                </div>
                                                <div class="col-md-5 col-xs-8" align="right">
                                                   <div class="my-order-price">
                                                       <input id="product_notes[<?= $product['product_id'] ?>]" name="product_notes[<?= $product['product_id'] ?>]" type="text" class="input-cart" value="" placeholder="Notes"> 
                                                   </div> 
                                                </div>
                                            </div>
                                        </li>
                                                    <?php } ?>
                                            <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-6 col-xs-8" align="left">
                                                   <div class="my-order-price">
                                                     <button type="button" class="btn btn-grey" data-dismiss="modal">CLOSE</button>
                                                   </div> 
                                                </div>
                                                <div class="col-md-6 col-xs-8" align="right">
                                                   <div class="my-order-price">
                                                       <button type="button" class="btn btn-default" id="missed_rejected_products" name="missed_rejected_products" data-order-id="<?php echo $order_id; ?>">SUBMIT</button>
                                                   </div> 
                                                </div>
                                            </div>
                                            </li>      
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
$(document).delegate('#missed_rejected_products', 'click', function (e) {
e.preventDefault();
$('#missed_rejected_products').prop('disabled', true);
var order_id = $(this).attr('data-order-id');
console.log($('#edit-address-form').serialize());

$.ajax({
    url: 'index.php?path=account/order/addMissedRejectedProducts&token=<?php echo $token; ?>',
    type: 'post',
    dataType: 'json',
    data:$('#edit-address-form').serialize(),
    async: true,
    success: function(json) {
    console.log(json); 
    if (json['status']) {
        $('#poModal-success-message').html(' Saved Successfully');
    }
    else {
        $('#poModal-success-message').html('Please try again');
    }
    },
    error: function(xhr, ajaxOptions, thrownError) {    
        $('#poModal-message').html("Please try again");
        return false;
    }
});
});
</script>
        </html>
        </form>
