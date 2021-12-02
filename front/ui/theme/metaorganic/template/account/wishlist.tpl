
<?php echo $header; ?>


                          <div class="col-md-9 nopl">
                            <div class="dashboard-profile-content">
                                <div class="my-order">
                                    <?php if (isset($wishlists)) { ?>
                                        <div class="order-details">
                                        <?php foreach ($wishlists as $wishlist) { ?>
                                            <div class="col-md-6">
                                                
                                                <li class="list-group-item">
                                                    <div class="my-order-block">
                                                        <div class="row">
                                                                 <div class="col-md-14">                                                                
                                                                    
                                                                     <h3 class="col-md-10 my-order-name"><?php echo $wishlist['name']; ?></h3>
                                                                         
                                                                        <a  style="float:right;" href="#" id="cancelWishlist" data-id='<?=$wishlist["wishlist_id"] ?>'>
                                                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <path d="M9 3V4H4V6H5V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V6H20V4H15V3H9ZM7 6H17V19H7V6ZM9 8V17H11V8H9ZM13 8V17H15V8H13Z" fill="#FF6464"/>
                                                                            </svg>
                                                                        </a>
                                                                         
                                                                </div>
                                                            </div>
                                                                     
                                                                     
                                                            <div class="row">
                                                                    <div class="col-md-14 my-order-date">Created on
                                                                    <?php echo  $wishlist['date_added']; ?>
                                                                    </div>
                                                            </div>
                                                                 <br>
                                                                 
                                                            <div class="row">
                                                                 <div class="col-md-14 my-order-count">Products : 
                                                                    <?php echo  $wishlist['product_count']; ?> Items
                                                                    </div>
                                                                    
                                                                     <span class="my-order-id"></span>
                                                                   <!-- <?php echo ($wishlist['product_count'] > 0 ?  '<a href="'.$wishlist['href'].'" class="btn btn-default btn-xs btn-accept-reject">' .View .' '. Products . ' (' .$wishlist['product_count'].')' .'Items'.'</a>' : $text_products_count. ' ' .$wishlist['product_count']); ?>-->

                                                                   
                                                            </div>
                                                            <br>                    

                                                            <div class="row" >
                                                                     <span style="float:right;">
                                                                     
                                                                   <?php echo ($wishlist['product_count'] > 0 ?  '<a href="'.$wishlist['href'].'" class="btn-newview">' .View .' '. Basket    .'</a>' : ''); ?>
                                                                     
                                                                     <!--<a href="'.$wishlist['href'].'"  data-id='<?=$wishlist["wishlist_id"] ?>'   class="btn-newview">View Basket</a>-->
                                                                     <a href="#" id="addWishlisttocart" data-id='<?=$wishlist["wishlist_id"] ?>'  style="color: #00f;" class="btn-newadd">Add To Cart</a>

                                                                    </span>
                                                             </div>
                                                            
                                                    </div>
                                                </li>
                                            <br>
                                                  
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
                                        <p>No lists found in your basket</p>
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

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $base ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

    <script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/javascript/jquery/infinitescroll/manual-trigger.js" ></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

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

    $(document).delegate('#cancelWishlist', 'click', function(e) {

        e.preventDefault();
        
        if(!window.confirm("Are you sure?")) {
            return false;
        }
        console.log("cancelWishlist click");
        console.log($(this).attr('data-id'));
        $('#cancelWishlist').html('Wait...');
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: 'index.php?path=account/wishlist/deleteWishlist',
            type: 'post',
            data: {
                wishlist_id: $(this).attr('data-id')
            },
            dataType: 'json',
            success: function(json) {
                console.log(json);
                
                setTimeout(function(){ window.location.reload(false); }, 1000);
            }
        });
    });
    
        $(document).delegate('#addWishlisttocart', 'click', function(e) {

        e.preventDefault();
        
        if(!window.confirm("Are you sure?")) {
            return false;
        }
        console.log("addWishlisttocart click");
        console.log($(this).attr('data-id'));
        $('#addWishlisttocart').html('Wait...');
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: 'index.php?path=account/wishlist/addWishlistProductToCart',
            type: 'post',
            data: {
                wishlist_id: $(this).attr('data-id')
            },
            dataType: 'json',
            success: function(json) {
                console.log(json);
                
               // setTimeout(function(){ window.location.reload(false); }, 1000);
               if (json.location != null && json.status == 'success') {
                        console.log(json.location);
                        var timer = setTimeout(function () {
                            window.location.href = json.location;
                        }, 1000);
                        return false;
                        //location = json.redirect;
                        //location = location;
                    }


            }
        });
    });

    </script>
    <style>
.my-order-title
{
/* position: static;
width: 118px;
height: 30px;
left: 0px;
top: 0px;
margin: 0px 253px;*/

font-family: Poppins;
font-style: normal;
font-weight: bold;
font-size: 20px;
line-height: 30px; 
display: flex;
align-items: center;
color: #FA8700;


/* Inside Auto Layout */

flex: none;
order: 0;
flex-grow: 0;

}
.my-order-header
{
    
}

.my-order-name{
     

font-family: Poppins;
font-style: normal;
font-weight: bold;
font-size: 20px;
line-height: 30px;color: #FA8700;padding-left:0px;
  
 
}
.my-order-date{position: static;
font-family: Poppins;
font-style: normal;
font-weight: 300;
font-size: 12px;
line-height: 20px; 
color: #000000;  
}
.my-order-count{position: static;
font-family: Poppins;
font-style: normal;
font-weight: 300;
font-size: 14px;
line-height: 22px; 
color: #000000;  
}


.btn-newview{    

border: 0.3px solid #000000;
box-sizing: border-box;
filter: drop-shadow(0px 6px 30px rgba(0, 0, 0, 0.18));
border-radius: 8px;
background: none;
color:#3F3D3D !important;
font-family: Poppins;
font-style: normal;
font-weight: 600;
font-size: 16px;
line-height: 24px;
margin: 0px 16px;
padding: 10px 12px;

  
}
.btn-newadd{  
  

background: green;
border: 0.3px solid #0C9D46;
box-sizing: border-box;
box-shadow: 0px 6px 30px rgba(90, 244, 170, 0.25);
border-radius: 8px;
 color:white !important;
 font-family: Poppins;
font-style: normal;
font-weight: 600;
font-size: 14px;
line-height: 20px;
margin: 0px 16px;
padding: 10px 12px;
}
    </style>
</body>

</html>
