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
                                                                    <h3 class="my-order-title label" style="background-color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2;"><?php echo $order['status']; ?></h3>

                                                                    <span class="my-order-date">ETA: <?php echo $order['eta_date']; ?>, <?php echo $order['eta_time']; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="my-order-info">
                                                                    <h3 class="my-order-title"><?php echo $order['store_name']; ?> - <?php echo $order['total']; ?></h3>

                                                                    <?php if($order['realproducts']) { ?>

                                                                        <span class="my-order-id"><?= $text_order_id?> <?php echo $order['order_id']; ?>  .  <?php echo $order['real_products']; ?> items</span>
                                                                        
                                                                        
                                                                    <?php } else { ?>

                                                                        <span class="my-order-id"><?= $text_order_id?> <?php echo $order['order_id']; ?>  .  <?php echo $order['products']; ?> items</span>

                                                                    <?php } ?>

                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3"><a href="<?php echo $order['href']; ?>" class="btn-link"><?= $text_view?> <?php echo $order['products']; ?> <?= $text_items_ordered?> </a>
                                                            <br/>

                                                            <?php if($order['realproducts']) { ?>
                                                                <a href="<?php echo $order['real_href']; ?>" class="btn-link"><?= $text_view?> <?php echo $order['real_products']; ?> <?= $text_real_items_ordered?> </a>
                                                            <?php } ?>
                                                            

                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="my-order-refund">
                                                        <i class="fa fa-money"></i> <span><?= $text_refund_text_part1 ?> <strong><?= $text_refund_text_part2 ?> </strong> <?= $text_refund_text_part3 ?></span>
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
                                                        
                                                        <div class="my-order-details">
                                                            <div class="row">
                                                                <div class="col-md-4"><?= $text_payment_options?></div>
                                                                <div class="col-md-8"><?php echo $order['payment_method']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li> 
                                                <li class="list-group-item">
                                                    <div class="my-order-showaddress">
                                                        <a class="btn-link" role="button" data-toggle="collapse" href="#<?= $order['order_id'] ?>" aria-expanded="false" aria-controls="<?= $order['order_id'] ?>"><?= $text_view_billing?></a>&nbsp;|&nbsp;<a class="btn-link" role="button" href="<?php echo ($order['realproducts'] ? $order['real_href'] : $order['href']) ;?>" aria-expanded="false" aria-controls="<?= $order['order_id'] ?>"><?= $text_view_order?></a>
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
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
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


    var page_category = 'order-list-page';
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

    $(document).ready(function() {
            var $container = $('.order-details');
            $container.infinitescroll({
                animate:true,
                navSelector  : '.pagination',    // selector for the paged navigation 
                nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
                itemSelector : '.order-details',
                loading: {
                    finishedMsg: 'No more orders to load.',
                    msgText: 'Loading...',
                    img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'
                    
                },
                errorCallback: function () { 
                    $('.load-more-text').html('<?= $text_load_more?>');
                    $('.load-more-loader').hide(); 
                }
            }, function(json, opts) {
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

    $(document).delegate('#cancelOrder', 'click', function(e) {

        e.preventDefault();
        
        if(!window.confirm("Are you sure?")) {
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
            success: function(json) {
                console.log(json);
                // if (json['status']) {
                //     alert("Order ID #"+orderId+" is successfully cancelled");
                    
                    
                // } else {
                //     alert("Order ID #"+orderId+" cancelling failed");
                // }

                setTimeout(function(){ window.location.reload(false); }, 1000);
            }
        });
    });

    setInterval(function() {
     location = location;
    }, 30 * 1000); // 60 * 1000 milsec
    

    </script>
</body>

</html>