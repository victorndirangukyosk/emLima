<?php echo $header; ?>
<div class="col-md-9 nopl">
    <div class="dashboard-cash-content">

        <div class="row">
            <div class="col-md-12">
                <div class="cash-info" style="padding-bottom: 50px;padding-top: 50px;"><h1><?= $text_balance ?></h1>
                </div>
            </div>


            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Pezesha Customer ID
                </div>
                <div class="col-md-6" id="pay_with" >
                    <?php echo $this->customer->getCustomerPezeshaId(); ?>
                </div>                                  

            </div>

            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Pezesha Customer UU-ID
                </div>
                <div class="col-md-6" id="pay_with" >
                    <?php echo $this->customer->getCustomerPezeshauuId(); ?>
                </div>                                  

            </div>

        </div>

        <div class="credit-details">
            <?php foreach ($credits  as $credit) { ?>
            <div class="my-order"><!-- 25 Dec 2015 -->
                <div class="list-group my-order-group">
                    <li class="list-group-item my-order-list-head"><i class="fa fa-clock-o"></i> <?= $text_activity?> <span><strong><?php echo $credit['date_added']; ?></strong></span><span>

                            <!-- <a href="#" data-toggle="modal" data-target="#contactusModal" class="btn btn-default btn-xs"><?= $text_report_issue ?> </a> -->

                        </span></li>
                    <li class="list-group-item">
                        <div class="my-order-block">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="my-order-delivery">
                                        <!-- <?php if($credit['amount'] >= 0) { ?>
                                          <h3 class="my-order-title">Credit</h3>
                                        <?php } else { ?>
                                              <h3 class="my-order-title">Debit</h3>
                                        <?php } ?> -->
                                        <span class="my-order-date"><?php echo $credit['description']; ?></span>
                                    </div>
                                </div>
                                <?php if($credit['plain_amount'] >= 0) { ?>
                                <div class="col-md-2" style="color: green"><?php echo $credit['amount']; ?></div>
                                <?php } else { ?>
                                <div class="col-md-2" style="color: red"><?php echo $credit['amount']; ?></div>
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


    var page_category = 'pezesha-page';
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
    $('#button-complete').on('click', function () {


    });
</script>


<?php if($redirect_coming) { ?>
<script type="text/javascript">
    $('#save-button').click();
</script>
<?php } ?>

<style>
    .option_pay {
        margin-top:-3px !important;
    }     
</style>
</body>
</html>