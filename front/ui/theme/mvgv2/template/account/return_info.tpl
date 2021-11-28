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
                            <div class="col-md-12">
                                <div class="my-order-view-content">
                                    <div class="my-order">
                                        <div class="list-group my-order-group">
                                            <li class="list-group-item my-order-list-head">
                                                
                                                <h2 class="my-order-list-title"><?= $order_info['store_name']?></h2>
                                                <span><?= $text_order_id_with_colon ?>
                                                    <a href="<?= $order_link ?>">
                                                        <span class="my-order-id-number">#<?php echo $order_id; ?>
                                                        </span>
                                                    </a>
                                                </span>
                                                 <span class="my-order-id-item"><i class="fa fa-clock-o"></i> <?= $text_placed_on?> <strong><?php echo $date_added; ?></strong>
                                                </span>

                                                <span class="my-order-id-item"><?= $text_return_id ?><span class="">#<?php echo $return_id; ?></span></span>
                                            </li>

                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="mycart-product-img"><img src="<?= $image ?>" alt="" class="img-responsive"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="my-order-price">
                                                            <h3> <?php echo $product; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $unit; ?></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="my-order-price">
                                                            <span><?= $text_quantity ?>
                                                                <span class="my-order-id-number"><?php echo $quantity; ?>
                                                                    
                                                                </span>
                                                            </span>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="my-order-price">
                                                            <h3> <?php echo $column_reason; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $reason; ?></span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="my-order-price">
                                                            <h3> <?php echo $column_opened; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $opened; ?></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="my-order-price">
                                                            <h3> <?php echo $column_comment; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $comment; ?></span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="my-order-price">
                                                            <h3> <?php echo $column_action; ?> </h3>
                                                            <p class="product-info"><span class="small-info"><?php echo $action; ?></span>
                                                            </p>
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
    <?php echo $footer; ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/side-menu-script.js"></script>

    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
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
</html>

