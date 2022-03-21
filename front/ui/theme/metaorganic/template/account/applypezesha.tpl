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
                    Copy Of Certificate Of Incorporation 
                </div>
                <div class="col-md-4" id="pay_with" >
                    <input id="copy_of_certificate_of_incorporation" name="copy_of_certificate_of_incorporation" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>
                <div class="col-md-2" id="pay_with">
                    <button type="button" id="copy_of_certificate_of_incorporation_button" name="copy_of_certificate_of_incorporation_button" class="btn btn-primary">UPLOAD</button>
                </div>

            </div>

            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of Bussiness Operating Permit
                </div>
                <div class="col-md-4" id="pay_with" >
                     <input id="copy_of_bussiness_operating_permit" name="copy_of_bussiness_operating_permit" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>                                  
                <div class="col-md-2" id="pay_with">
                    <button type="button" id="copy_of_bussiness_operating_permit_button" name="copy_of_bussiness_operating_permit_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>

            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of ID Of Bussiness Owner / Managing Director
                </div>
                <div class="col-md-4" id="pay_with" >
                     <input id="copy_of_id_of_bussiness_owner_managing_director" name="copy_of_id_of_bussiness_owner_managing_director" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>
                <div class="col-md-2" id="pay_with">
                    <button type="button" id="copy_of_id_of_bussiness_owner_managing_director_button" name="copy_of_id_of_bussiness_owner_managing_director_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>

            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;" id="loan_offers">

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
    $('#pezesha-button-loan').on('click', function () {
        $.ajax({
            type: 'get',
            url: 'index.php?path=payment/pezesha/loanoffers',
            dataType: 'html',
            cache: false,
            success: function (html) {
                $('#loan_offers').html(html);
            }

        });

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
