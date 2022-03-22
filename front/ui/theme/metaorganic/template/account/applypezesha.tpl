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
                    DOB / Date Of Incorporation 
                </div>
                <div class="col-md-6" id="pay_with" >
                   <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="DOB / Date Of Incorporation" data-date-format="dd/mm/YYYY" id="input-date-added" class="form-control date" autocomplete="off" required/>
                </div>
        </div>
            
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    KRA PIN
                </div>
                <div class="col-md-6" id="pay_with" >
                    <input type="text" value="<?php echo $kra; ?>" size="30" placeholder="KRA PIN" value="<?php echo $kra ?>" name="kra" maxlength="100" id="kra" class="form-control input-lg" />
                </div>
        </div>
            
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Gender
                </div>
                <div class="col-md-6" id="pay_with" >
                <label class="control control--radio" style="display: initial !important;"> 
                    <?php if($gender == 'male') {?> 
                        <input type="radio" name="gender" data-id="8" value="male" checked="checked"> Male 
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="8" value="male"> Male
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>

                <label class="control control--radio" style="display: initial !important;">
                    <?php if($gender == 'female') {?> 
                        <input type="radio" name="gender" data-id="9" value="female" checked="checked"> Female
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="9" value="female"> Female
                    <?php } ?>                   
                    <div class="control__indicator"></div>
                </label>

                <label class="control control--radio" style="display: initial !important;">
                    <?php if($gender == 'other') {?> 
                        <input type="radio" name="gender" data-id="8" value="other" checked="checked"> Other
                    <?php } else {?>
                    <input type="radio" name="gender" data-id="8" value="other"> Other
                    <?php } ?>
                    <div class="control__indicator"></div>
                </label>
                </div>
        </div>    
        
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    National ID
                </div>
                <div class="col-md-6" id="pay_with" >
                    <input type="text" name="national_id" id="national_id" placeholder="National ID" value="<?php echo $national_id; ?>" class="form-control input-lg" />
                </div>
        </div>              
                    
        <form method="POST" enctype="multipart/form-data" id="copy_of_certificate_of_incorporation_form">
            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of Certificate Of Incorporation 
                </div>
                <div class="col-md-4" id="pay_with" >
                    <input id="copy_of_certificate_of_incorporation" name="copy_of_certificate_of_incorporation" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>
                <div class="col-md-2" id="pay_with">
                    <button type="submit" id="copy_of_certificate_of_incorporation_button" name="copy_of_certificate_of_incorporation_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>
        </form>    

        <form method="POST" enctype="multipart/form-data" id="copy_of_bussiness_operating_permit_form">
            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of Bussiness Operating Permit
                </div>
                <div class="col-md-4" id="pay_with" >
                     <input id="copy_of_bussiness_operating_permit" name="copy_of_bussiness_operating_permit" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>                                  
                <div class="col-md-2" id="pay_with">
                    <button type="submit" id="copy_of_bussiness_operating_permit_button" name="copy_of_bussiness_operating_permit_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>
        </form>    

        <form method="POST" enctype="multipart/form-data" id="copy_of_id_of_bussiness_owner_managing_director_form">    
            <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Copy Of ID Of Bussiness Owner / Managing Director
                </div>
                <div class="col-md-4" id="pay_with" >
                     <input id="copy_of_id_of_bussiness_owner_managing_director" name="copy_of_id_of_bussiness_owner_managing_director" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                </div>
                <div class="col-md-2" id="pay_with">
                    <button type="submit" id="copy_of_id_of_bussiness_owner_managing_director_button" name="copy_of_id_of_bussiness_owner_managing_director_button" class="btn btn-primary">UPLOAD</button>
                </div>
            </div>
        </form>

        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available; text-align: center;" id="loan_offers">
            <button type="submit" id="submit_info_to_pezesha" name="submit_info_to_pezesha" class="btn btn-primary">SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA</button>
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
$('#copy_of_certificate_of_incorporation_button').on('click', function(e) {
    e.preventDefault();
    var file_data = $('#copy_of_certificate_of_incorporation').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafiles',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log(response);
            }

    });
});
$('#copy_of_bussiness_operating_permit_button').on('click', function(e) {
    e.preventDefault();
    var file_data = $('#copy_of_bussiness_operating_permit').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafilestwo',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log(response);
            }

    });
});
$('#copy_of_id_of_bussiness_owner_managing_director_button').on('click', function(e) {
    e.preventDefault();
    var file_data = $('#copy_of_id_of_bussiness_owner_managing_director').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafilesthree',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log(response);
            }

    });
});
$('#submit_info_to_pezesha').on('click', function(e) {
    e.preventDefault();
    $.ajax({
            type: 'get',
            url: 'index.php?path=account/applypezesha/userregistration',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            },
            complete: function() {
            $.ajax({
            type: 'get',
            url: 'index.php?path=account/applypezesha/accrptterms',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log('accept terms response');
            console.log(response);
            console.log('accept terms response');
            }
            });    
            $.ajax({
            type: 'get',
            url: 'index.php?path=account/applypezesha/dataingestion',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log('data ingestion response');
            console.log(response);
            console.log('data ingestion response');
            }
            });    
            },
            success: function (response) {
            console.log('user registration response');
            console.log(response);
            console.log('user registration response');
            }

    });
});
</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript">
$('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
});
</script>
<style>
    .option_pay {
        margin-top:-3px !important;
    }     
</style>
</body>
</html>
