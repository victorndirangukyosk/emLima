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
       
       <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-6" id="pay_with" >
                    Credit Period
                </div>
                <div class="col-md-6" id="pay_with" >
                    <select class="form-control input-lg" id="credit_period" name="credit_period"><option value="">Select Credit Period</option><option value="30+7 days - 1.5%">30+7 days - 1.5%</option><option value="30+15 days - 2%">30+15 days - 2%</option></select>
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
        
        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available;">
                <div class="col-md-12" id="pay_with" >
                    <a href="#" type="button" class="btn-link text_green" data-toggle="modal" data-target="#addressModal"><i class="fa fa-check-square-o"></i> Terms & Condtions</a>
                </div>
        </div>

        <div class="col-md-12" style="border: 1px solid #d7dcd6;padding: 10px;margin: 15px;width: -webkit-fill-available; text-align: center;" id="loan_offers">
            <button type="submit" id="submit_info_to_pezesha" name="submit_info_to_pezesha" class="btn btn-primary">SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA</button>
        </div>
        <div class="col-md-12">   
        <div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px;">
        </div>    
        <div class="alert alert-success" style="font-size: 14px;" id="success_msg" style="margin-bottom: 7px;"></div>
        </div>    
        </div>



    </div>
</div>
<?php echo $footer; ?>

<div class="addressModal">
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Terms & Condtions</h2>
                        </div>
                        <div id="address-message" class="col-md-12" style="color: red">
                        </div>
                        <div id="address-success-message" style="color: green">
                        </div>
                        <div class="addnews-address-form">

                            <div class="form-group" id="parent_terms_conditions" style="height:130px; overflow: auto;">
                                <div class="col-md-12" id="terms_conditions">
                                    <li>This is a financing facility from the Pezesha Africa LTD.</li>
                                    <li>You can pay for the facility 30 days after delivery.</li>
                                    <li>You can borrow up to "AVAILABLE LIMIT".</li>
                                    <li>The facility(loan) attracts Ksh 150 service fee.</li>
                                    <li>The facility(loan) attracts 1.5% interest over the loan tenure.</li>
                                    <li>Pay on time to avoid late fees and penalties.</li>
                                    <li>The terms and conditions apply.</li>
                                    Press Continue to accept the above terms and conditions
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="singlebutton" name="singlebutton" data-terms="0" type="button" class="btn btn-primary" data-dismiss="modal" disabled>AGREE</button>
                                    <button id="cancelbutton" name="cancelbutton" type="button" class="btn btn-grey  cancelbut" data-dismiss="modal">DECLINE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
$('#success_msg').hide();
$('#error_msg').hide();    
$('#copy_of_certificate_of_incorporation_button').on('click', function(e) {
    e.preventDefault();
    $('#success_msg').hide();
    $('#error_msg').hide();
    if( document.getElementById("copy_of_certificate_of_incorporation").files.length == 0 ){
    $('#error_msg').html('Copy Of Certificate Of Incorporation Sholud Not Be Empty!');
    $('#error_msg').show();
    return false;
    }
    
    const fi = document.getElementById('copy_of_certificate_of_incorporation');
    if (fi.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    $('#error_msg').html('Copy Of Certificate Of Incorporation Sholud Be Less Than 2 MB!');
                    $('#error_msg').show();
                return false;
                }
            }
    }
    
    var fileInput = document.getElementById('copy_of_certificate_of_incorporation');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(filePath)) {
    $('#error_msg').html('Copy Of Certificate Of Incorporation File Type Invalid!!');
    $('#error_msg').show();            
    return false;
    } 
    
    var file_data = $('#copy_of_certificate_of_incorporation').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafiles',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('#copy_of_certificate_of_incorporation_button').prop('disabled', true);   
            },
            complete: function() {
            setInterval(function(){
            $('#copy_of_certificate_of_incorporation_button').prop('disabled', false);
            $('#copy_of_certificate_of_incorporation_button').html('UPLOAD');
            }, 1000);
            },
            success: function (response) {
            console.log(response);
            
            if(response.status == 200) {
            $('#copy_of_certificate_of_incorporation_button').html('<i class="fa fa-check" aria-hidden="true"></i>');   
            }
            
            if(response.status == 500) {
            $('#copy_of_certificate_of_incorporation_button').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            
            }

    });
});
$('#copy_of_bussiness_operating_permit_button').on('click', function(e) {
    e.preventDefault();
    $('#success_msg').hide();
    $('#error_msg').hide();
    if( document.getElementById("copy_of_bussiness_operating_permit").files.length == 0 ){
    $('#error_msg').html('Copy Of Bussiness Operating Permit Sholud Not Be Empty!');
    $('#error_msg').show();    
    return false;
    }
    
    const fi = document.getElementById('copy_of_bussiness_operating_permit');
    if (fi.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    $('#error_msg').html('Copy Of Bussiness Operating Permit Sholud Be Less Than 2 MB!');
                    $('#error_msg').show();
                return false;
                }
            }
    }
    
    var fileInput = document.getElementById('copy_of_bussiness_operating_permit');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(filePath)) {
    $('#error_msg').html('Copy Of Bussiness Operating Permit File Type Invalid!!');
    $('#error_msg').show();            
    return false;
    }
    
    var file_data = $('#copy_of_bussiness_operating_permit').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    //alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafilestwo',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('#copy_of_bussiness_operating_permit_button').prop('disabled', true);   
            },
            complete: function() {
            setInterval(function(){
            $('#copy_of_bussiness_operating_permit_button').prop('disabled', false);
            $('#copy_of_bussiness_operating_permit_button').html('UPLOAD');
            }, 1000);
            },
            success: function (response) {
            console.log(response);
            
            if(response.status == 200) {
            $('#copy_of_bussiness_operating_permit_button').html('<i class="fa fa-check" aria-hidden="true"></i>');   
            }
            
            if(response.status == 500) {
            $('#copy_of_bussiness_operating_permit_button').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            
            }

    });
});
$('#copy_of_id_of_bussiness_owner_managing_director_button').on('click', function(e) {
    e.preventDefault();
    $('#success_msg').hide();
    $('#error_msg').hide();
    if( document.getElementById("copy_of_id_of_bussiness_owner_managing_director").files.length == 0 ){
    $('#error_msg').html('Copy Of ID Of Bussiness Owner / Managing Director Sholud Not Be Empty!');
    $('#error_msg').show();    
    return false;
    }
    
    const fi = document.getElementById('copy_of_id_of_bussiness_owner_managing_director');
    if (fi.files.length > 0) {
            for (var i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    $('#error_msg').html('Copy Of ID Of Bussiness Owner / Managing Director Sholud Be Less Than 2 MB!');
                    $('#error_msg').show();
                return false;
                }
            }
    }
    
    var fileInput = document.getElementById('copy_of_id_of_bussiness_owner_managing_director');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if (!allowedExtensions.exec(filePath)) {
    $('#error_msg').html('Copy Of ID Of Bussiness Owner / Managing Director File Type Invalid!!');
    $('#error_msg').show();            
    return false;
    }
    
    var file_data = $('#copy_of_id_of_bussiness_owner_managing_director').prop('files')[0];
    var form_data = new FormData();
    form_data.append("file", file_data);
    //alert(form_data);
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/pezeshafilesthree',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('#copy_of_id_of_bussiness_owner_managing_director_button').prop('disabled', true);   
            },
            complete: function() {
            setInterval(function(){
            $('#copy_of_id_of_bussiness_owner_managing_director_button').prop('disabled', false);
            $('#copy_of_id_of_bussiness_owner_managing_director_button').html('UPLOAD');
            }, 1000);
            },
            success: function (response) {
            console.log(response);
            
            if(response.status == 200) {
            $('#copy_of_id_of_bussiness_owner_managing_director_button').html('<i class="fa fa-check" aria-hidden="true"></i>');   
            }
            
            if(response.status == 500) {
            $('#copy_of_id_of_bussiness_owner_managing_director_button').html('<i class="fa fa-times" aria-hidden="true"></i>');
            }
            
            }

    });
});
$('#singlebutton').on('click', function(e) {
e.preventDefault();
$('#singlebutton').attr('data-terms', '1');
});

$('#cancelbutton').on('click', function(e) {
e.preventDefault();
$('#singlebutton').attr('data-terms', '0');
});

$('#submit_info_to_pezesha').on('click', function(e) {
    $('#success_msg').hide();
    $('#error_msg').hide(); 
    e.preventDefault();
    
    if($('#singlebutton').attr('data-terms') == '0' || $('#singlebutton').attr('data-terms') == ''){
    $('#error_msg').html('Accept Terms & Conditions!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name=dob]").val() == ''){
    $('#error_msg').html('DOB Sholud Not Be Empty!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name=kra]").val() == ''){
    $('#error_msg').html('KRA PIN Sholud Not Be Empty!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name=national_id]").val() == ''){
    $('#error_msg').html('Nation ID Sholud Not Be Empty!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("input[name='gender']:checked").val() == ''){
    $('#error_msg').html('Please Select Gender!');
    $('#error_msg').show(); 
    return false;
    }
    
    if($("select[name='credit_period']").val() == ''){
    $('#error_msg').html('Please Select Credit Period!');
    $('#error_msg').show(); 
    return false;
    }
    
    $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/updatecustomerinfo',
            data : { dob : $("input[name=dob]").val(), kra : $("input[name=kra]").val(), national_id : $("input[name=national_id]").val(), gender : $("input[name='gender']:checked").val(), credit_period : $("select[name='credit_period']").val() },
            dataType: 'json',
            cache: false,
            async: false,
            beforeSend: function() {
            $("#submit_info_to_pezesha").prop("disabled", true); 
            $('#submit_info_to_pezesha').html('<i class="fa fa-spinner" aria-hidden="true"></i>SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA');
            },
            complete: function() {
            $.ajax({
            type: 'post',
            url: 'index.php?path=account/applypezesha/userregistration',
            data : { credit_period : $("select[name='credit_period']").val() },
            dataType: 'json',
            cache: false,
            async: false,
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
            console.log(response.data);
            console.log('accept terms response');
            }
            });    
            $.ajax({
            type: 'get',
            url: 'index.php?path=account/applypezesha/dataingestion',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            },
            complete: function() {
            $("#submit_info_to_pezesha").prop("disabled", false); 
            $('#submit_info_to_pezesha').html('SUBMIT FOR CREDIT APPROVAL THROUGH PEZESHA');    
            },
            success: function (response) {
            console.log('data ingestion response');
            console.log(response);
            console.log('data ingestion response');
            }
            });    
            },
            success: function (response) {
            console.log('user registration response');
            if(response.data.status == 422) {
            console.log(response.errors);
            $.each(response.errors, function (key, data) {
            $('#error_msg').html(data);
            $('#error_msg').show();
            })
            }
            
            if(response.data.status == 200) {    
            $('#success_msg').html('You have been successfully registred for pezesha credit. Your application is under review.');
            $('#success_msg').show();
            
            $("#dob").prop("readonly", true);
            $("#kra").prop("readonly", true);
            $("#gender").prop("readonly", true);
            $("#national_id").prop("readonly", true);
            
            $("#copy_of_certificate_of_incorporation").prop("disabled", true);
            $("#copy_of_bussiness_operating_permit").prop("disabled", true);
            $("#copy_of_id_of_bussiness_owner_managing_director").prop("disabled", true);
            
            $("#copy_of_certificate_of_incorporation_button").prop("disabled", true);
            $("#copy_of_bussiness_operating_permit_button").prop("disabled", true);
            $("#copy_of_id_of_bussiness_owner_managing_director_button").prop("disabled", true);
            
            $("#submit_info_to_pezesha").prop("disabled", true);
            
            setInterval(function(){ window.location.replace('/'); }, 10000);
            }
            console.log(response);
            console.log('user registration response');
            }

            });    
            },
            success: function (response) {
            console.log('updatecustomerinfo response');
            console.log(response);
            console.log('updatecustomerinfo response');
            }
    });
});

$('#parent_terms_conditions').scroll(function() {
  console.log('SCROLLING');
  var disable = $('#terms_conditions').height() != ($(this).scrollTop() + $(this).height());
  $('#singlebutton').prop('disabled', disable);
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
