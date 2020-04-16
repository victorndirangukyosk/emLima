<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="kdt:page" content="home-page"> 
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <title><?= $store_heading_title ?></title>
    <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    
    
    <!-- Bootstrap -->
    <link href="<?= $base?>front/ui/theme/mvgv2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base?>front/ui/theme/mvgv2/css/style.css?v=5.1">
    <link rel="stylesheet" type="text/css" href="<?= $base?>front/ui/theme/mvgv2/css/abhishek.css?v=2.0.6">

    <link rel="stylesheet" type="text/css" href="<?= $base?>front/ui/theme/mvgv2/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?= $base?>front/ui/theme/mvgv2/css/sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base?>front/ui/theme/mvgv2/css/drawer.min.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/css/drawer.min.css">


    
    <link rel="stylesheet" type="text/css" href="<?= $base?>front/ui/theme/mvgv2/css/drawer.css">

</head>

<body class="drawer drawer--top">

    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><center><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></center>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>


    <div class="drawer-nav how-it-works section" role="navigation">
        <div class="inner">
            <div class='how-it-works-close'>
                <i class='ic-icon ic-icon-x'></i>
            </div>

            <div class='how-it-works-title'>
                <h4><?= $text_get_groceries ?> <br class='mobile'><?= $text_deliver_in ?></h4>
            </div>
            <div class="row">
                
            
                <?php foreach($blocks as $block) {?>
                    <div class="col-sm-4">
                            <div class='how-it-works-section'><div class='icon-image' style="background-image:url('<?= $block['image'] ?>')"></div>
                                <h5 class='title'><?= $block['title']; ?></h5>
                                <span class='content'>
                                <?= $block['description']; ?>
                                </span></div>
                    </div>
                    
                <?php } ?>
            </div>
        </div>
        </div>
        </div>
    </div>
      
    <div class="header-transparent">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-5"><a href="<?= $home; ?>" class="btn-link-white"><img src="<?= $logo ?>"></a>


                </div>
                <!-- <div class="store-logo"> <img src="<?= $logo ?>"></div> -->
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-7">
                    <div class="header-link">
                        <!-- <a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#signupModal-popup"><?= $text_store_working ?></a> -->
                        <a  class="btn-link-white" id="test-drawer" type="button" style="cursor: pointer; cursor: hand;"> <?= $text_store_working ?></a>
                        <div class="my-account-dropdown">
                            <?php if($is_login) { ?>
                            
                                <a href="#" class="dropdown-toggle btn-link-white" data-toggle="dropdown"><?= $text_account ?></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <div class="user-profile">

                                        <a href="<?= $account ?>" > <span class="user-profile-img"><img src="<?= $base?>front/ui/theme/mvgv2/images/user-profile.png"></span></a>

                                            <a href="<?= $account ?>" > <span class="user-name"><?= $full_name ?></span> </a>
                                        </div>
                                    </li>
                                    <!-- <li><a href="<?= $account ?>" ><?= $text_my_profile ?></a></li> -->
                                    <li><a href="<?= $order ?>" ><i class="fa fa-reorder"></i><?= $text_orders ?></a></li>
                                    <li><a href="<?= $wishlist ?>" ><i class="fa fa-list-ul"></i><?= $text_my_wishlist?></a></li>
                                    <?php if($this->config->get('config_credit_enabled')) { ?>

                                        <li><a href="<?= $credit ?>" ><i class="fa fa-money"></i><?= $text_my_cash ?></a></li>
                                    <?php } ?>

                                    <?php
                                    preg_match("/[^\/]+$/", "http://www.mydomainname.com/m/groups/view/test", $matches);
                                    $last_word = $matches[0];
                                    ?>
                                    
                                    <li><a href="<?= $address ?>" ><i class="fa fa-address-book"></i><?= $label_my_address ?></a></li>
                                    <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>
                                    <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                                    <li><a href="<?= $logout ?>"><i class="fa fa-power-off"></i><?= $text_logout ?></a></li>

                                </ul>
                            
                                <?php }else{ ?>
                                <a href="#" class="dropdown-toggle btn-link-white" data-toggle="dropdown"><?= $text_account ?></a>
                                <ul class="dropdown-menu" role="menu">                                    
                                    <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#phoneModal"><i class="fa fa-sign-in"></i><?= $text_sign_in ?></a></li>
                                    <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#signupModal-popup"><i class="fa fa-user-plus"></i><?= $text_register ?></a></li>
                                    <li><a href="#" class="btn-link-white" type="button" data-toggle="modal" data-target="#contactusModal"><i class="fa fa-phone-square"></i><?= $contactus ?></a></li>
                                    <li><a href="<?= $help ?>"><i class="fa fa-question-circle"></i><?= $faq ?></a></li>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-image">
        <div class="container">
            <div class="row">

                <div class="col-md-offset-3 col-md-6">

                    <?php if($store_collection_logo) { ?>
                            <center>
                                <img src="<?= $store_collection_logo ?>" style="border-radius: 50%;background-color: #fff;    width: 155px;margin-bottom: -77px;padding: 10px;">    
                            </center>
                    <?php } ?>
                    
                    
                    <div class="store-find-block">
                        <?php if(!$store_collection_logo) { ?>
                            <div class="store-logo"> <img src="<?= $logo ?>"></div>
                            <div class="store-find">
                            <div class="store-head">
                        <?php } else { ?>
                            <div class="store-find" style="padding: 30px;">
                            <div class="store-head" style="    margin-top: 50px;">
                        <?php }  ?>

                            

                                <h1><?= $store_collection_name ?></h1>
                                <h4><?= $text_enter_zipcode_title_collection ?></h4>
                            </div>
                            <!-- Text input-->
                            <div class="store-form">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-12 control-label sr-only" for="zipcode"><?= $text_enter_zipcode ?></label>
                                        <div class="col-md-12">
                                           <?php if($this->config->get('config_store_location') == 'autosuggestion') { ?>
                                                <input name="zipcode" id="searchTextField"  type="text" class="form-control input-md zipcode-enter" required="" placeholder="Enter location">
                                            <?php } else { ?>
                                                <input name="zipcode" id="searchTextField"  type="text" class="form-control input-md zipcode-enter" required="" placeholder="<?= $zipcode_mask ?>">
                                            <?php } ?>
                                            <input type="hidden" name="store_list_url" value="<?= $this->url->link('information/locations/stores', '&filter=' . $filter) ?>">

                                             <input type="hidden" id="store_location" value="<?= $this->config->get('config_store_location'); ?>">

                                            <input type="hidden" name="collection_id" value="<?= $collection_id ?>">
                                        </div>
                                    </div>
                                    <!-- Button -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label sr-only" for="submit">submit</label>
                                        <div class="col-md-12">
                                            <button id="submit" name="submit" onclick="detect_location();" class="btn btn-default btn-block btn-lg collection-start-shopping">
                                            <span class="start-shopping-text"><?= $text_shop_now ?></span>
                                            <div class="loader" style="display: none;"></div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if($is_login) { ?>
                                    <h3><?= $text_welcome_user ?> <?= $full_name ?></h3>
                            <?php } else { ?>
                                <p><?= $text_have_account ?> <a href="#" data-toggle="modal" data-target="#phoneModal" ><?= $text_log_in?></a></p>
                            <?php } ?>
                            
                        </div>
                        <div class="store-footer">
                            <p> <?= $text_get_delivered ?>  </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="space-small bg-default bdr-btm">
    <div class="container">
        <div class="row">

            <center>
                <h2> <?= $text_we_located_at?> </h2>
            </center>
            
            
        </div>

        <?php foreach($store_collections as $city) { ?>
            <div class="row">
               
                <h2 class="section-heading"  style="    margin: 0 0 30px 0;
    font-size: 28px;
    line-height: 40px;
    text-align: center;
    font-weight: 600;" id="arizona"><?= $city['city_name']; ?></h2>


                    <div class="ic-retailers-container">
<ul class="retailer-cards-list unstyled" style="list-style: none;
    margin: 0;
    padding: 0;
    text-align: center;">


                <?php foreach( $city['stores'] as $store) { ?>

                    <li style="display: inline-block;margin: 10px;">
                        <div class="retailer-card" style="background: #fff;box-sizing: border-box;margin: 7px 0px;text-align: center;width: 292px;border: 1px solid #EEF5F4;position: relative;">

                            <div class="card-header" style="padding-top: 0;display: table;cursor: inherit;width: 100%;height: 100px;">
                                 <?php $mapsrc = "https://maps.googleapis.com/maps/api/staticmap?zoom=15&size=296x146&maptype=roadmap&markers=color:red%7C".$store['latitude'] .",". $store['longitude']."&key=".$this->config->get('config_google_api_key');

                                    ?>

                                <img alt="<?= $store['address']?>" class="map-image" src="<?= $mapsrc ?>" style="    width: 100%;height: auto;vertical-align: middle;border: 0;overflow: hidden;">
                            </div>
                            <div class="card-body" style="text-decoration: none;padding: 10px 15px;">       
                                <h2 class="name ic-truncate">
                                    
                                    <?= $store['store_name'] ?>
                                </h2>

                                <h2 class="name ic-truncate" style="    min-height: 22px;font-weight: 600;color: #43B02A;font-size: 18px;line-height: 20px;margin: 10px 0px;">
                                    
                                    <?= $store['address'] ?>
                                </h2>
                                <p><?= $store['city_name'] ?></p>
                            </div>
                        </div>
                    </li>

                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>


    </div>
</div>
    
    <!-- Phone Modal -->
    <?= $login_modal ?>
    <?= $signup_modal ?>
    <?= $forget_modal ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
    <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

    <script src="<?= $base?>front/ui/javascript/home.js"></script> 
    <script src="<?= $base?>front/ui/javascript/bxslider/jquery.bxslider.min.js"></script>

    <script src="<?= $base?>front/ui/theme/mvgv2/js/sweetalert.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
    <!--<script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>-->
    <!-- <script src="<?= $base?>front/ui/theme/mvgv2/js/iscroll.min.js"></script>
    <script src="<?= $base?>front/ui/theme/mvgv2/js/drawer.min.js" type="text/javascript"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/drawer/3.2.1/js/drawer.min.js" type="text/javascript"></script>


    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>


    <!-- <script src="<?= $base?>front/ui/theme/mvgv2/js/bootstrap-datepicker.pt-BR.js"></script> -->
    <script src="https://cdn.jsdelivr.net/bootstrap.datepicker-fork/1.3.0/js/locales/bootstrap-datepicker.pt-BR.js"></script>

    <script type="text/javascript" src="<?= $base?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
<script type="text/javascript">


    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
        language: '<?php echo $config_language ?>'
    });
    
    /*jQuery(function($){
        console.log("signup mask");
       $("#phone_number").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
    });*/
    jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });

    jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });


    $('.zipcode-enter').keydown(function(event) {

        console.log("zipcode enter");
        // enter has keyCode = 13, change it if you want to use another button
        if (event.keyCode == 13) {
          var text = $('.start-shopping-text').html();
            $('.start-shopping-text').html('');
            $('.loader').show();

            var zipcode = $('input[name=\'zipcode\']').val();
            var url = "<?= $this->url->link('information/locations/stores') ?>";

            checkCollectionStoreisAvailable(zipcode,url,text,<?php echo $collection_id; ?>);

            /*var str1 = zipcode.substr(0,5);
            var str2 = zipcode.substr(6,3);

            console.log(str1+str2);
            console.log(isNaN(str1+str2));*/

            /*if($('input[name="zipcode"]').val().length > 8 && !isNaN(str1+str2)) {
                
                var zipcode = $('input[name=\'zipcode\']').val();
                console.log("zipcode"+zipcode);
                
                
                
            }else{
                $('.start-shopping-text').html(text);
                $('.loader').hide();
                $('#no-zip').show();
                $('#no-zip').html('No Zipcode Found');
            }*/
          return false;
        }
      });

    $(document).delegate('#test-drawer', 'click', function(e) {
        $('.drawer-nav').addClass('how-it-works-open');
        $('.header-transparent').addClass('how-it-works-open');
        $('.hero-image').addClass('how-it-works-open');
        
    });
    
    $("body").on("click",function(e) {
        if ($('.how-it-works-open').length >= 1) {
            $('.drawer-nav').removeClass('how-it-works-open'); 
            $('.drawer.drawer--top').removeClass('drawer-open');
            $('.header-transparent').removeClass('how-it-works-open');
            $('.hero-image').removeClass('how-it-works-open');
        }
    });
    $(document).delegate('.how-it-works-close', 'click', function(e) {
        $('.drawer-nav').removeClass('how-it-works-open'); 
        $('.drawer.drawer--top').removeClass('drawer-open');
        $('.header-transparent').removeClass('how-it-works-open');
        $('.hero-image').removeClass('how-it-works-open');
    });
    
    function validateInp(elem) {
        var validChars = /[0-9]/;
        var strIn = elem.value;
        var strOut = '';
        for(var i=0; i < strIn.length; i++) {
          strOut += (validChars.test(strIn.charAt(i)))? strIn.charAt(i) : '';
        }
        elem.value = strOut;
    }

</script>
<script type="text/javascript">

    
    
    jQuery(function($){
        console.log("mask");
       $("#searchTextField").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
    });
    
</script>
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

               console.log("visitorIDif");
               console.log("visitorIDif"+visitorID);
               console.log(visitorID);
               $.ajax({
                    url: 'index.php?path=common/home/saveVisitorId&visitor_id='+visitorID,
                    type: 'post',
                    dataType: 'json',
                    success: function(json) {
                        console.log(json);
                    }
                });
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);


    var page_category = 'home';
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
