<?php echo $header; ?>
                        <div class="col-md-9 nopl">
                            <div class="dashboard-cash-content">
                                 
                                <div class="row">
                                     <div class="col-md-12">
                                            <div class="cash-info"><h1><?= $text_balance ?></h1>
                                            <div class="cash-block">
                                              <span class="your-cash"> <?= $total ?>  </span>
                                            </div>
                                            <a href="<?= $home ?>" class="btn btn-primary"><?= $text_shopping?></a>
                                            </div>
                                     </div>
                                </div>
                                <?php if ($rewards) { ?>
                                 <div class="reward-details">
                                    <?php foreach ($rewards  as $reward) { ?>
                                    <div class="my-order"><!-- 25 Dec 2015 -->
                                      <div class="list-group my-order-group">
                                          <li class="list-group-item my-order-list-head"><i class="fa fa-clock-o"></i> <?= $text_activity?> <span><strong><?php echo $reward['date_added']; ?></strong></span><span>

                                          <!-- <a href="#" data-toggle="modal" data-target="#contactusModal" class="btn btn-default btn-xs"><?= $text_report_issue ?> </a> -->

                                          </span></li>
                                          <li class="list-group-item">
                                              <div class="my-order-block">
                                                  <div class="row">
                                                      <div class="col-md-10">
                                                          <div class="my-order-delivery">
                                                              <span class="my-order-date"><?php echo $reward['description']; ?></span>
                                                          </div>
                                                      </div>
                                                     <?php if($reward['plain_points'] >= 0) { ?>
                                                                <div class="col-md-2" style="color: green"><?php echo $reward['points']; ?></div>
                                                              <?php } else { ?>
                                                                    <div class="col-md-2" style="color: red"><?php echo $reward['points']; ?></div>
                                                              <?php } ?>
                                                      
                                                  </div>
                                              </div>
                                          </li>
                                      </div>
                                  </div>
                                    <?php } ?>
                                    <?php } else { ?>
                                      <center class="text-center" colspan="5"><?php echo $text_empty; ?></center>
                                <?php } ?>
                                 </div>
                                    

                                <div class="text-right" style='display: none;'>
                                    <?php echo $pagination; ?>
                                </div>

                                <?php if(!empty($pagination)) { ?>
                                    <div id="button-area">
                                        <button class="load_more btn btn-default center-block" type="button">
                                            <span class="load-more-text"><?= $text_load_more?></span>
                                            <div class="load-more-loader" style="display: none;"></div>
                                        </button>    
                                    </div>
                                <?php } ?>

                                <!-- <div class="row">
                                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                                </div>
                                  <br />
                                  <?php echo $content_bottom; ?> -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php echo $footer; ?>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/manual-trigger.js" ></script>

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


    var page_category = 'reward-page';
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
            var $container = $('.reward-details');
            $container.infinitescroll({
                animate:true,
                navSelector  : '.pagination',    // selector for the paged navigation 
                nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
                itemSelector : '.reward-details',
                loading: {
                    finishedMsg: '<?php echo $text_no_more ?>',
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

        $(document).delegate('#contactus', 'click', function() {
                console.log("contactus click");
                $.ajax({
                    url: 'index.php?path=information/contact',
                    type: 'post',
                    data: $('#contactus-form').serialize(),
                    dataType: 'json',
                    success: function(json) {
                        console.log(json);
                        if (json['status']) {
                            $('#contactus-message').html('');
                            $('#contactus-success-message').html(json['text_message']);
                            setTimeout(function(){ window.location.reload(false); }, 1000);
                            
                        } else {
                            $error = '';

                            if(json['error_email']){
                                $error += json['error_email']+'<br/>';
                            }
                            if(json['error_enquiry']){
                                $error += json['error_enquiry']+'<br/>';
                            }
                            if(json['error_name']){
                                $error += json['error_lastname']+'<br/>';
                            }
                            $('#contactus-message').html($error);
                        }
                    }
                });
            });
    </script>
    </body>
</html>