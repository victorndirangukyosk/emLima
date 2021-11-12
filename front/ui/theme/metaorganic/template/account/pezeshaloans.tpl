<?php echo $header; ?>
<div class="container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row">
        <div class="col-md-9">
                <div id="pending" class="tab-pane fade in active">
                    <table id="employee" class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="order_id">Order Id </th>
                                <th class="order_id">Order Date</th>
                                <th class="order_id">Order Total</th>
                                <th class="order_id">Amount Payable</th>
                                <th class="order_id">Payment Method</th>
                            </tr>
                        </thead>
                        <tbody id="emp_body">
                        </tbody>
                    </table>
                    <div id="pager">
                        <ul id="paginationpending" class="pagination-sm"></ul>
                    </div>        
                </div>
        </div>
    </div>                        
</div>
<?php echo $footer; ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
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
                    var page_category = 'my-transactions-page';
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
                    })(page_category);</script>

<?php } ?>
<!--  jQuery -->

<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>
<?php if($redirect_coming) { ?>
<script type="text/javascript">
    $('#save-button').click();
</script>

<?php } ?>
<style>
    .nav-tabs>li {
        width: 33.3%;
    }

    .option_pay {
        margin-top:-3px !important;
    }
    .amount
    {
        text-align: center; 
        vertical-align: middle;
    }
    .order_id
    {
        text-align: center; 
        vertical-align: middle;
    }
</style>
</body>
</html>