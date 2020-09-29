<?php echo $header; ?>

<div class="col-md-9 nopl">
    <div class="dashboard-profile-content">
        <div class="my-order">
            <?php if ($orders) { ?>
            <div class="order-details">
                <?php foreach ($orders as $order) { ?>

                <div class="list-group my-order-group">
                    <li class="list-group-item my-order-list-head">
                        <i class="fa fa-clock-o"></i> <?= $text_placed_on?> <span><strong><?php echo $order['date_added']; ?></strong></span>, <?php echo $order['time_added']; ?> <span>

                            <div class="pull-right">
                                <button type="button" style="height:25px" onclick="excel(<?=$order["order_id"] ?>,'<?=$order["order_company"] ?>');" data-toggle="tooltip" title="Download Ordered Products"
                                        class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
                            </div>

                            <?php if($order['status'] == 'Arrived for Delivery'){?>
                                                     <a href="<?php echo $order['accept_reject_href']?>"  class="btn btn-default btn-xs btn-accept-reject" >Accept Delivery</a>
                                                    <?php } ?>
                            <?php if($order['shipped']) { ?>

                            <a href="#" id="cancelOrder" data-id='<?=$order["order_id"] ?>' class="btn btn-danger btn-xs btn-custom-remove"><?= $text_cancel ?></a>


                            <?php } else { ?>
                            <a href="#" data-toggle="modal" data-target="#contactusModal"  class="btn btn-default btn-xs"><?= $text_report_issue ?></a>
                            <?php } ?>


                        </span>
                    </li>
                    <li class="list-group-item">
                        <div class="my-order-block">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="my-order-delivery">
                                        <h3 class="my-order-title label" id="orderstatus<?= $order['order_id']; ?>" style="background-color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2;"><?php echo $order['status']; ?></h3>

                                        <span class="my-order-date">ETA: <?php echo $order['eta_date']; ?>, <?php echo $order['eta_time']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-order-info">
                                        <h3 class="my-order-title"><?php if($order['order_company'] == NULL) { echo $order['store_name']; ?> (<?php echo $order['name']; ?>) <?php } else { echo $order['order_company']; } ?> - <?php echo $order['total']; ?></h3>

                                        <?php if($order['realproducts']) { ?>

                                        <span class="my-order-id"><?= $text_order_id?> <?php echo $order['order_id']; ?>  .  <?php echo $order['real_products']; ?> items</span>


                                        <?php } else { ?>

                                        <span class="my-order-id"><?= $text_order_id?> <?php echo $order['order_id']; ?>  .  <?php echo $order['products']; ?> items</span>

                                        <?php } ?>


                                    </div>
                                </div>
                                <div class="col-md-3"><a href="<?php echo $order['href']; ?>" class="btn-link text_green"><?= $text_view?> <?php echo $order['products']; ?> <?= $text_items_ordered?> </a>
                                    <br/>

                                    <?php if($order['realproducts']) { ?>
                                    <a href="<?php echo $order['real_href']; ?>" class="btn-link text_green"><?= $text_view?> <?php echo $order['real_products']; ?> <?= $text_real_items_ordered?> </a>
                                    <?php } ?>


                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="my-order-details" style="border: none !important;">
                            <?php if($order['parent_details'] != NULL && !empty($_SESSION['parent']) && $_SESSION['parent'] > 0) { ?>
                            <div class="row">
                                <div class="col-md-4">Parent User Email</div>
                                <div class="col-md-8"><?php echo $order['parent_details']; ?></div>
                            </div> 
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-4"><?= $text_payment_options?></div>
                                <div class="col-md-8"><?php echo $order['payment_method']; ?></div>

                                <?php if ($order['payment_method'] == 'mPesa Online'){
                                if(!empty($order['payment_transaction_id'])){
                                $payment_status = '<span style="color:green">Success</span>';
                                }else{
                                $payment_status = '<span style="color:red">Failed</span>';
                                }

                                if($order['status'] == 'Cancelled'){
                                $payment_status = '<span style="color:#'.$order['order_status_color'].'">Cancelled</span>';
                                }
                                ?>
                                <div class="col-md-4">Payment Status</div>
                                <div class="col-md-8"><?php echo $payment_status; ?></div>
                                <?php if(!empty($order['payment_transaction_id'])){?>
																<div class="col-md-4">Transaction Id</div>
																<div class="col-md-8"><?php echo $order['payment_transaction_id']; ?></div>
	
																<?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item my-order-details-block">
                        <div class="collapse" id="<?= $order['order_id'] ?>">

                            <div class="my-order-details">
                                <div class="row">
                                    <div class="col-md-4"><?= $text_delivery_address?></div>
                                    <?php if(isset($order['shipping_address'])) { ?>
                                    <div class="col-md-8">
                                        <?= $order['shipping_address']['address'] ?> <br/>
                                        <?= $order['shipping_address']['city'] ?>, <?= $order['shipping_address']['zipcode'] ?></div>
                                    <?php } else { ?>
                                    <div class="col-md-8"> </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="my-order-details">
                                <div class="row">
                                    <div class="col-md-4"><?= $text_payment ?></div>
                                    <div class="col-md-8">
                                        <div class="">
                                            <?= $order['order_total'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </li> 
                    <li class="list-group-item">
                        <div class="my-order-showaddress">  
                            <a class="btn-link text_green" role="button" data-toggle="collapse" href="#<?= $order['order_id'] ?>" aria-expanded="false" aria-controls="<?= $order['order_id'] ?>"><?= $text_view_billing?></a>&nbsp;|&nbsp;<a class="btn-link text_green" role="button" href="<?php echo ($order['realproducts'] ? $order['real_href'] : $order['href'].'&order_status='.urlencode($order['status'])) ;?>" aria-expanded="false" aria-controls="<?= $order['order_status'] ?>"><?= $text_view_order?></a>
                            <?php if($order['edit_order'] != NULL && (($order['order_approval_access_role'] == 'head_chef' && $order['head_chef'] == 'Pending') || ($order['order_approval_access_role'] == 'procurement_person' && $order['procurement'] == 'Pending'))) { ?> |&nbsp;<a class="btn-link text_green" role="button" href="<?php echo $order['edit_order']; ?>" id="editorder<?php echo $order['order_id']; ?>" aria-expanded="false">Edit Order</a> <?php } ?>
                        </div>
                    </li>

                    <?php if($order['head_chef'] == 'Pending' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'head_chef') { ?>
                    <li class="list-group-item">
                        <div class="my-order-showaddress" id="<?php echo $order['order_id']; ?>">  
                            <a href="#" id="approve_order_head_chef" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn btn-default btn-xs">APPROVE ORDER</a>
                            <a href="#" id="reject_order_head_chef" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn btn-default btn-xs">REJECT ORDER</a>
                        </div>
                    </li>
                    <?php } else if($order['head_chef'] == 'Approved' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'head_chef') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['head_chef']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } else if($order['head_chef'] == 'Rejected' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'head_chef') { ?> 
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['head_chef']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } ?>

                    <?php if($order['procurement'] == 'Pending' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'procurement_person') { ?>
                    <li class="list-group-item">
                        <div class="my-order-showaddress" id="<?php echo $order['order_id']; ?>">  
                            <a href="#" id="approve_order_procurement" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn btn-default btn-xs">APPROVE ORDER</a>
                            <a href="#" id="reject_order_procurement" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn btn-default btn-xs">REJECT ORDER</a>
                        </div>
                    </li>
                    <?php } else if($order['procurement'] == 'Approved' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'procurement_person') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['procurement']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } else if($order['procurement'] == 'Approved' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'procurement_person') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['procurement']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if($order['status'] == 'Order Approval Pending' && $order['parent_approve_order'] == 'Need Approval' && $order['parent_approval'] == 'Pending') { ?>
                    <li class="list-group-item">
                        <div class="my-order-showaddress" id="<?php echo $order['order_id']; ?>">  
                            <a href="#" id="approve_order" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn btn-default btn-xs">APPROVE ORDER</a>
                            <a href="#" id="reject_order" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn btn-default btn-xs">REJECT ORDER</a>
                        </div>
                    </li>
                    <?php } elseif($order['parent_approve_order'] == 'Need Approval' && $order['parent_approval'] != 'Pending') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['parent_approval']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                    <li class="list-group-item">
                        <div class="my-order-refund" style="font-size:13px;">
                            <i class="fa fa-money"></i> <span><?= $text_refund_text_part1 ?> <strong><?= $text_refund_text_part2 ?> </strong> <?= $text_refund_text_part3 ?></span>
                        </div>
                    </li>
                </div>
                <?php } ?>
                <div class="text-right" style='display: none;'>
                    <?php echo $pagination; ?>
                </div>
            </div>

            <?php if(!empty($pagination)) { ?>
            <div id="button-area">
                <button class="load_more btn btn-default center-block" type="button">
                    <span class="load-more-text"><?= $text_load_more?></span>
                    <div class="load-more-loader" style="display: none;"></div>
                </button>    
            </div>
            <?php } ?>

            <?php } else { ?>
            <p><?php echo $text_empty; ?></p>
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

<?php echo $footer; ?>

<script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?= $base; ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

<script type="text/javascript" src="<?= $base; ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/javascript/jquery/infinitescroll/manual-trigger.js" ></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

                                    var __kdt = __kdt || [];

                                    var public_key = '<?php echo $konduto_public_key ?>';

                                    console.log("public_key");
                                    console.log(public_key);
                                    __kdt.push({"public_key": public_key}); // The public key identifies your store
                                    __kdt.push({"post_on_load": false});
                                    (function () {
                                        var kdt = document.createElement('script');
                                        kdt.id = 'kdtjs';
                                        kdt.type = 'text/javascript';
                                        kdt.async = true;
                                        kdt.src = 'https://i.k-analytix.com/k.js';
                                        var s = document.getElementsByTagName('body')[0];

                                        console.log(s);
                                        s.parentNode.insertBefore(kdt, s);
                                    })();

                                    var visitorID;
                                    (function () {
                                        var period = 300;
                                        var limit = 20 * 1e3;
                                        var nTry = 0;
                                        var intervalID = setInterval(function () {
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


                                    var page_category = 'order-list-page';
                                    (function () {
                                        var period = 300;
                                        var limit = 20 * 1e3;
                                        var nTry = 0;
                                        var intervalID = setInterval(function () {
                                            var clear = limit / period <= ++nTry;
                                            if (typeof (Konduto.sendEvent) !== "undefined") {

                                                Konduto.sendEvent(' page ', page_category); //Programmatic trigger event
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

    $(document).ready(function () {
        var $container = $('.order-details');
        $container.infinitescroll({
            animate: true,
            navSelector: '.pagination', // selector for the paged navigation 
            nextSelector: '.pagination a', // selector for the NEXT link (to page 2)
            itemSelector: '.order-details',
            loading: {
                finishedMsg: 'No more orders to load.',
                msgText: 'Loading...',
                img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'

            },
            errorCallback: function () {
                $('.load-more-text').html('<?= $text_load_more?>');
                $('.load-more-loader').hide();
            }
        }, function (json, opts) {
            $('.load-more-text').html('<?= $text_load_more?>');
            $('.load-more-loader').hide();
        });

        $(window).unbind('.infscr');

        $(document).on('click', '.load_more', function () {
            var text = $('.load-more-text').html();
            $('.load-more-text').html('');
            $('.load-more-loader').show();
            $container.infinitescroll('retrieve');
            return false;
        });

        /**/
    });

    $(document).delegate('#approve_order', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrder',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Approved'
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });

    $(document).delegate('#approve_order_head_chef', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var parent_div = $(this).parent("div");
        var role = 'head_chef';
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Approved',
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });

    $(document).delegate('#reject_order_head_chef', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var order_status = 'Rejected';
        var customer_id = $(this).attr('data-custid');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var role = 'head_chef';
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));

        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: order_status,
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Rejected</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });

    $(document).delegate('#approve_order_procurement', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var role = 'procurement';
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Approved',
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });

    $(document).delegate('#reject_order_procurement', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var role = 'procurement';
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Rejected',
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });

    $(document).delegate('#reject_order', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var order_status = 'Rejected';
        var customer_id = $(this).attr('data-custid');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);

        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));

        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrder',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: order_status
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Rejected</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });


    $(document).delegate('#cancelOrder', 'click', function (e) {

        e.preventDefault();

        if (!window.confirm("Are you sure?")) {
            return false;
        }
        console.log("cancelOrder click");
        console.log($(this).attr('data-id'));
        $('#cancelOrder').html('Wait...');
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: 'index.php?path=account/order/refundCancelOrder',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id')
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                // if (json['status']) {
                //     alert("Order ID #"+orderId+" is successfully cancelled");


                // } else {
                //     alert("Order ID #"+orderId+" cancelling failed");
                // }

                setTimeout(function () {
                    window.location.reload(false);
                }, 1000);
            }
        });
    });

    setInterval(function () {
        location = location;
    }, 6000 * 1000); // 60 * 1000 milsec




    function excel(order_id, order_company) {
        //alert(order_company);
        url = 'index.php?path=account/order/export_products_excel&order_id=' + order_id + '&company=' + order_company;
        location = url;
    }

</script>
</body>

</html>