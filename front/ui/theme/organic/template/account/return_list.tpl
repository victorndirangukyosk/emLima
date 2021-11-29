<?php echo $header; ?>

                          <div class="col-md-9 nopl">
                            <div class="dashboard-profile-content">
                                <div class="my-order">
                                    <?php if ($returns) { ?>
                                        <div class="order-details">
                                        <?php foreach ($returns as $return) { ?>

                                            <div class="list-group my-order-group">
                                                <li class="list-group-item my-order-list-head">
                                                    <i class="fa fa-clock-o"></i> <?= $text_placed_on?> <span><strong><?php echo $return['date_added']; ?></strong></span> <span>
                                                    
                                                      <a href="#" data-toggle="modal" data-target="#contactusModal"  class="btn btn-default btn-xs"><?= $text_report_issue ?></a>
                                                   
                                                    </span>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="my-order-block">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="my-order-delivery">
                                                                    <h3 class="my-order-title"><?php echo $return['status']; ?></h3>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <div class="my-order-info">
                                                                    <h3 class="my-order-title"><?php echo $return['name']; ?></h3>
                                                                    <span class="my-order-id"><?= $text_order_id?> <?php echo $return['order_id']; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1"><a href="<?php echo $return['href']; ?>" class="btn-link"><?= $text_view?> </a></div>
                                                        </div>
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

    </script>
</body>

</html>
