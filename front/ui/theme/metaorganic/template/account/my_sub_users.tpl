<?php //echo '<pre>';print_r($_SESSION);exit;?>

<?php echo $header; ?>
<div class="container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row"> 
        <div class="col-md-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#addSubUser">Add Sub User</a></li>
                <li><a data-toggle="tab" href="#allsubusers">Sub users</a></li>
                <li><a data-toggle="tab" href="#assign_approvals">Assign Order Approvals</a></li>
            </ul>

            <div class="tab-content">
                <?php if(isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])){?>
        <div class="alerter">
        <div class="alert alert-info normalalert">
        <p class="notice-text">Success: <?php echo $_SESSION['success_msg']?>.</p>
        </div>

        </div>
        <?php $_SESSION['success_msg'] ='';?>
        <?php }?>
                <div id="allsubusers" class="tab-pane fade">
                    <?php //echo'<pre>';print_r($_SESSION);exit;?>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Contact Person Name</th>
                                <th>Branch Email</th>
                                <th>Phone No</th>
                                <th>Customer Group</th>
                                <th>Branch Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($sub_users)){ ?>
                            <?php foreach($sub_users as $user){ ?>
                            <tr id="user<?php echo $user['customer_id']; ?>">
                                <td><?php echo $user['firstname'].' '.$user['lastname'];?></td>
                                <td><?php echo $user['email'];?></td>
                                <td><?php echo $user['telephone'];?></td>
                                <td><?php echo $user['customer_group'];?></td>
                                <td><?php echo $user['company_name'];?></td>
                                <td class="status<?php echo $user['customer_id']; ?>"><?php echo ($user['approved']==0) ? 'Unverified': 'Verified'?></td>
                                <td><?php if($user['approved'] == 0) { ?> <a data-confirm="Activate sub user!" class="btn btn-success useractivate" data-active="1" data-store-id="<?php echo $user['customer_id']; ?>" data-toggle="tooltip" title="Activate user"><i class="fa fa-check"></i></a> <?php } ?>
                                    <?php if($user['approved'] == 1) { ?> <a data-confirm="De activate sub user!" class="btn btn-success useractivate" data-active="0" data-store-id="<?php echo $user['customer_id']; ?>" data-toggle="tooltip" title="De activate user"><i class="fa fa-times"></i></a> <?php } ?>
                                    <a data-confirm="Delete sub user!" class="btn btn-success userdelete" data-active="0" data-store-id="<?php echo $user['customer_id']; ?>" data-toggle="tooltip" title="Delete sub user"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            <?php } ?>
                            <?php }else{ ?>
                            <tr style="text-align:center">
                                <td colspan="5">No User found</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div id="addSubUser" class="tab-pane fade in active">
                    <form  autocomplete="off" method="post" action="<?php echo $action?>" id="add-user-form" enctype="multipart/form-data" class="form-horizontal">
                        <div class="secion-row">
                            <br />

                            <fieldset>
                                <div class="form-group required has-feedback">
                                    <label for="name" class="col-sm-3 control-label">Contact Person First Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" value="" size="30" placeholder="Contact Person First Name" name="firstname" maxlength="100" id="name" class="form-control input-lg" />
                                        <?php if($error_firstname) { ?>
                                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-lastname">Contact Person Last Name</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="text" name="lastname" value="" placeholder="Contact Person Last Name" id="input-lastname" class="form-control input-lg" />
                                        <?php if($error_lastname) { ?>
                                        <div class="text-danger"><?php echo $error_lastname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-email">Branch Email</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="email" name="email" id="email" value="" placeholder="Branch Email" id="input-email" class="form-control input-lg" />
                                        <?php if($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>


                                <div class="form-group required has-feedback">
                                    <label for="name" class="col-sm-3 control-label">Branch Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" value="" size="30" placeholder="Branch Name" name="company_name" maxlength="100" id="name" class="form-control input-lg" />
                                        <?php if($error_companyname) { ?>
                                        <div class="text-danger"><?php echo $error_companyname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>


                                <div class="form-group required has-feedback">
                                    <label for="name" class="col-sm-3 control-label">Branch Address</label>
                                    <div class="col-sm-6">
                                        <input type="text" value="" size="30" placeholder="Branch Address" name="company_address" maxlength="100" id="name" class="form-control input-lg" />
                                        <?php if($error_companyaddress) { ?>
                                        <div class="text-danger"><?php echo $error_companyaddress; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="flat">Branch Location</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input  name="modal_address_locality" id="txtPlaces" type="text"  class="form-control input-md LocalityId" required="">
                                        <input type="hidden" id="latitude" name="latitude" value=""/>
                                        <input type="hidden" id="longitude" name="longitude" value=""/>
                                        <input type="hidden" id="zipcode" name="zipcode" value=""/>
                                        <input type="hidden" id="customaddress" name="customaddress" value=""/>
                                    </div>
                                </div>


                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_phone; ?></label>

                                    <div class="col-sm-6 col-xs-12 input-group" style="padding-right: 15px;padding-left: 15px;">

                                        <span class="input-group-btn">

                                            <!-- <p id="button-reward" class="phonesetbut" >
                                                 <font style="vertical-align: inherit;">
                                                   <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                     +<?= $this->config->get('config_telephone_code') ?>
                                                   </font></font></font>
                                                 </font>
                                             </p>-->
                                            <p  class="phonesetbut" >

                                                <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                +<?= $this->config->get('config_telephone_code') ?>
                                                </font></font></font>
                                                </font>
                                            </p>

                                        </span>

                                        <input type="tel" name="telephone" id="tel" value=""  id="input-telephone" class="form-control input-lg" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 & amp;
                                                & amp;
                                                event.charCode <= 57" minlength="9" maxlength="9" />

                                        <?php if ($error_telephone) { ?>
                                        <div class="text-danger"><?php echo $error_telephone; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- <div class="form-group required">
                                   <label class="col-sm-3 control-label" for="input-date-added"><?php echo $entry_dob; ?></label>
                                   <div class="col-sm-6 col-xs-12">
                                       <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="<?php echo $entry_dob; ?>" data-date-format="dd/mm/YYYY" id="input-date-added" class="form-control date" />
                                       <?php if ($error_dob) { ?>
                                     <div class="text-danger"><?php echo $error_dob; ?></div>
                                     <?php } ?>
                                   </div>
                                 </div>--!>
                
                
                                <div class="form-group  has-feedback">
                                  <label for="name" class="col-sm-3 control-label"><?= $entry_fax ?></label>
                                  <div class="col-sm-6">
                                    <input type="text" value="<?php echo $fax; ?>" size="30" placeholder="Tax No" name="fax" maxlength="100" id="name" class="form-control input-lg" readonly=""/>
                                    <?php if($error_fax) { ?>
                                    <div class="text-danger"><?php echo $error_fax; ?></div>
                                    <?php } ?>
                                  </div>
                                </div>
                
                                <!-- <div class="form-group">
                                  <label class="col-sm-3 control-label" for="input-date-added"><?php echo $entry_fax; ?></label>
                                  <div class="col-sm-6 col-xs-12">
                                      <input type="text" name="fax" id="tax_number" value="<?php echo $fax; ?>" placeholder="<?php echo $taxnumber_mask; ?>" class="form-control" />
                                    <?php if ($error_tax) { ?>
                                      <div class="text-danger"><?php echo $error_tax; ?></div>
                                    <?php } ?>
                                  </div>
                                </div> -->
                                <input type="hidden" name="tax" id="tax_number" value="" placeholder="<?php echo $taxnumber_mask; ?>" class="form-control" readonly="" />

                                <div class="form-group required" style="display:none">
                                    <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_gender; ?></label>
                                    <div class="col-sm-6 col-xs-12">
                                        <label class="control control--radio" style="display: initial !important;">



                                            <input type="radio" name="gender" data-id="8" value="female"> <?= $text_female ?>


                                            <div class="control__indicator"></div>
                                        </label>

                                        <label class="control control--radio" style="display: initial !important;">

                                            <input type="radio" name="gender" data-id="9" value="male"> <?= $text_male ?>



                                            <div class="control__indicator"></div>
                                        </label>

                                        <label class="control control--radio" style="display: initial !important;">
                                            <?php if($gender == 'other') {?>
                      <input type="radio" name="gender" data-id="8" value="other" checked="checked"> <?= $text_other ?>
                      <?php } else {?>
                      <input type="radio" name="gender" data-id="8" value="other"> <?= $text_other ?>
                      <?php } ?>


                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_password; ?></label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="password" name="password" id="password" autocomplete="new-password" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control input-lg" />
                                        <?php if ($error_password) { ?>
                                        <div class="text-danger"><?php echo $error_password; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-password"><?php echo $entry_confirmpassword; ?></label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="password" name="confirmpassword" id="confirmpassword" placeholder="<?php echo $entry_confirmpassword; ?>" id="input-confirmpassword" class="form-control input-lg" />
                                        <?php if ($error_confirmpassword) { ?>
                                        <div class="text-danger"><?php echo $error_confirmpassword; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!--<div class="form-group required">
                                    <label class="col-sm-3 control-label" for="address">Address Type</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="select-locations">
                                            <div class="col-sm-3 col-xs-6">
                                                <label class="control control--radio">Home
                                                    <input type="radio" name="modal_address_type" value="home" checked="checked" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <label class="control control--radio">Office
                                                    <input type="radio" value="office" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <label class="control control--radio">Other
                                                    <input type="radio" value="other" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->

                                <!-- Text input-->
                                <!--<div class="form-group required">
                                    <label class="col-sm-3 control-label" for="flat">House No. and Building Name</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input id="flat" name="modal_address_flat" type="text" placeholder="45, Sunshine Apartments" class="form-control input-lg" required="">
                                    </div>
                                </div>-->

                                <?php if ($site_key) { ?>
                                <div class="form-group  ">
                                    <label class="col-sm-3 control-label" for="input-date-added"></label>
                                    <div class="col-sm-6 col-xs-12 pl0 pr0">

                                        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" style="padding-left:16px"></div>
                                        <?php if ($error_captcha) { ?>
                                        <div class="text-danger"><?php echo $error_captcha; ?></div>
                                        <?php } ?>
                                    </div>

                                </div>
                                <?php } ?>

                            </fieldset>

                        </div>
                        <div class="col-sm-2 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 63px">
                            <button type="submit" data-style="zoom-out" id="save-button" onclick="return validateAndSubmitForm()" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
                        </div>
                    </form>
                </div>
                <div id="assign_approvals" class="tab-pane fade">
                    <form  autocomplete="off" method="post" action="<?php echo $action?>" id="add-user-form" enctype="multipart/form-data" class="form-horizontal">
                        <div class="secion-row">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alerter" style="display: none;">
                                        <div class="alert alert-info normalalert">
                                            <p class="notice-text">Order Approvals Assigned!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />

                            <fieldset>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">First Level Approver</label>
                                    <div class="col-sm-4">
                                        <select class="form-control input-lg" id="head_chef" name="head_chef">
                                        </select>
                                    </div>
                                    <div class="col-sm-2 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 63px">
                                        <button type="submit" data-style="zoom-out" id="assign_head_chef" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-lastname">Second Level Approver</label>
                                    <div class="col-sm-4">
                                        <select class="form-control input-lg" id="procurement_person" name="procurement_person">
                                        </select>
                                    </div>
                                    <div class="col-sm-2 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 63px">
                                        <button type="submit" data-style="zoom-out" id="assign_procurement_person" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-subcustomerorderapproval">Sub Customer Order Approval</label>
                                    <div class="col-sm-4">
                                        <select class="form-control input-lg" id="sub_customer_order_approval" name="sub_customer_order_approval">
                                            <option value="1" <?php if($sub_customer_order_approval == 1) { echo 'selected'; } ?>>Required</option>
                                            <option value="0" <?php if($sub_customer_order_approval == 0) { echo 'selected'; } ?>>Not Required</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 col-sm-pull-2 secion-row text-center" style="margin-bottom: 20px; float: right; margin-right: 63px">
                                        <button type="submit" data-style="zoom-out" id="assign_sub_customer_order_approval" class="btn btn-default"><span class="ladda-label"><?= $button_save ?></span><span class="ladda-spinner"></span></button>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                    </form>
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
</div>
</div>
<?php echo $footer; ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?= $base?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
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


                                var page_category = 'my-account-page';
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
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>
<script>
    google.maps.event.addDomListener(window, 'load', function () {
        var options = {
            //types: ['geocode'], // or '(cities)' if that's what you want?
            componentRestrictions: {country: "KE"}
        };

        var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'), options);
        google.maps.event.addListener(places, 'place_changed', function () {
            var place = places.getPlace();
            var address = place.formatted_address;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var mesg = "Address: " + address;
            mesg += "\nLatitude: " + latitude;
            mesg += "\nLongitude: " + longitude;
            mesg += "\place: " + place;
            console.log(place);
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            $('#customaddress').val(address);
        });
    });
</script>
<script type="text/javascript">
    $('button[id^=\'button-custom-field\']').on('click', function () {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
        $('#form-upload input[name=\'file\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: 'index.php?path=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $(node).button('loading');
                    },
                    complete: function () {
                        $(node).button('reset');
                    },
                    success: function (json) {
                        $(node).parent().find('.text-danger').remove();

                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $(node).parent().find('input').attr('value', json['code']);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    //--></script>
<!--  jQuery -->

<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script type="text/javascript" src="<?= $base ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>

<script src="<?= $base ?>front/ui/theme/mvgv2/js/jquery.maskedinput.min.js" type="text/javascript"></script>

<script type="text/javascript">
    /*jQuery(function($){
     console.log("signup mask");
     $("#tel").mask("(99) 99999-9999",{autoclear:false,placeholder:"(##) #####-####"});
     });*/
    /*jQuery(function($){
     console.log("mask");
     $("#tel").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
     });*/
    jQuery(function ($) {
        console.log("tax mask");
        $("#tax_number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });

    $('.date').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });

    function changeOrderIdForPay(orderId, amount_to_pay) {
        $('input[name="order_id"]').val(orderId);
        $('#mpesa_amount').val(amount_to_pay);
        $('div#payment_options').hide();
        $('div#payment_options').focus();
        /* $('html, body').animate({
         scrollTop: $("#payment_options").offset().top
         }, 2000);
         */
    }

    function payOptionSelected() {
        //total_pending_amount
        var radioValue = $("input[name='pay_option']:checked").val();
        var total_pending_amount = $("input[name='total_pending_amount']").val();
        if (radioValue == 'pay_full') {
            $('#mpesa_amount').attr('readonly', true);
            $('#mpesa_amount').val(total_pending_amount);
        } else {
            $('#mpesa_amount').attr('readonly', false);
            $('#mpesa_amount').val('');
        }
    }
    function validateAndSubmitForm() {
        var return_var = true;
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

        $('.text-danger').remove();
        if ($("input[name='firstname']").val() == "") {
            return_var = false;
            $('<div class="text-danger">First Name must be between 1 and 32 characters!</div>').insertAfter($("input[name='firstname']"));
        }
        if ($("#email").val() == "") {
            return_var = false;
            $('<div class="text-danger">Email is mandatory</div>').insertAfter($("input[name='email']"));
        } else if (expr.test($("#email").val()) == false) {
            return_var = false;
            $('<div class="text-danger">Email address is not valid</div>').insertAfter($("input[name='email']"));
        }

        if ($("input[name='telephone']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Telephone is mandatory</div>').insertAfter($("input[name='telephone']"));
        } else if ($("input[name='telephone']").val().length < 9) {
            return_var = false;
            $('<div class="text-danger">Telephone is not valid</div>').insertAfter($("input[name='telephone']"));
        }


        if ($("input[name='company_name']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Please fill Company Name</div>').insertAfter($("input[name='company_name']"));
        }

        if ($("input[name='company_address']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Please fill Company Address</div>').insertAfter($("input[name='company_address']"));
        }

        if ($("input[name='password']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Password is mandatory!</div>').insertAfter($("input[name='password']"));
        }

        if ($("input[name='confirmpassword']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Confirm Password is mandatory!</div>').insertAfter($("input[name='confirmpassword']"));
        }

        if ($("input[name='password']").val() != $("input[name='confirmpassword']").val()) {
            return_var = false;
            $('<div class="text-danger">Password & Confirm Password should be same!</div>').insertAfter($("input[name='password']"));
        }

        if ($("input[name='modal_address_flat']").val() == "") {
            return_var = false;
            $('<div class="text-danger">House No. and Building Name is mandatory!</div>').insertAfter($("input[name='modal_address_flat']"));
        }

        if ($("input[name='modal_address_locality']").val() == "") {
            return_var = false;
            $('<div class="text-danger">Your Location is mandatory!</div>').insertAfter($("input[name='modal_address_locality']"));
        }

        /*if(grecaptcha.getResponse() == ""){
         return_var = false;
         $('<div class="text-danger">Please validate captcha!</div>' ).insertAfter( $(".g-recaptcha"));
         }*/
        return return_var;
    }
</script>

<style>
    .nav-tabs>li {
        width: 33.3%;
    }

    .option_pay {
        margin-top:-3px !important;
    }
    .table-bordered>tbody>tr>td {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
<script>
    $(document).delegate('.useractivate', 'click', function () {
        var choice = confirm($(this).attr('data-confirm'));
        if (choice) {
            console.log('User Activate!');
            var user_id = $(this).attr('data-store-id');
            var active_status = $(this).attr('data-active');

            if (active_status == 0) {
                $(this).find('i').toggleClass('fa-times fa-check');
                console.log(active_status + ' ' + 'Active Status');
                $(this).attr('data-confirm', 'Activate sub user!');
                $(this).attr('data-active', '1');
                $(this).attr('title', 'Activate user');
                $('.status' + user_id).html('Unverified');
            }

            if (active_status == 1) {
                $(this).find('i').toggleClass('fa-check fa-times');
                console.log(active_status + ' ' + 'Active Status');
                $(this).attr('data-confirm', 'De activate sub user!');
                $(this).attr('data-active', '0');
                $(this).attr('title', 'De activate user');
                $('.status' + user_id).html('Verified');
            }

            $.ajax({
                url: 'index.php?path=account/sub_users/ActivateSubUsers',
                type: 'post',
                data: {user_id: user_id, active_status: active_status},
                dataType: 'json',
                success: function (json) {
                    console.log(json);
                    if (active_status == 0) {
                        $(this).find('i').toggleClass('fa-times fa-check');
                        console.log(active_status + ' ' + 'Active Status');
                        $('.status' + user_id).html('Unverified');
                    }

                    if (active_status == 1) {
                        $(this).find('i').toggleClass('fa-times fa-check');
                        console.log(active_status + ' ' + 'Active Status');
                        $('.status' + user_id).html('Verified');
                    }

                }
            });
        }
    });
    $(document).delegate('#email', 'blur', function () {
        console.log($(this).val());
        $.ajax({
            url: 'index.php?path=account/sub_users/EmailUnique',
            type: 'post',
            data: {email: $(this).val()},
            dataType: 'json',
            success: function (json) {
                if (json.success == false) {
                    console.log(json.success);
                    $("#save-button").prop('disabled', true);
                    $('<div class="text-danger">Email address should be unique</div>').insertAfter($("input[name='email']"));
                }

                if (json.success == true) {
                    console.log(json.success);
                    $("#save-button").prop('disabled', false);
                    $('.text-danger').remove();
                }
            }
        });
    });
//setTimeout(function () { location.reload(true); }, 50000);
    $(document).delegate('.userdelete', 'click', function () {
        var choice = confirm($(this).attr('data-confirm'));
        if (choice) {
            console.log('User Delete!');
            var user_id = $(this).attr('data-store-id');

            $.ajax({
                url: 'index.php?path=account/sub_users/DeleteSubUsers',
                type: 'post',
                data: {user_id: user_id},
                dataType: 'json',
                success: function (json) {
                    console.log(json);
                    $('#user' + user_id).remove();
                }
            });
        }
    });
    $(document).ready(function () {
        $.ajax({
            url: 'index.php?path=account/sub_users/getSubusers',
            type: 'post',
            dataType: 'json',
            success: function (json) {
                if (json.success == false) {
                    console.log(json.success);
                }

                if (json.success == true) {
                    console.log(json.success);
                    console.log(json.data);

                    var $procurement_person = $('#procurement_person');
                    var $head_chef = $('#head_chef');
                    $('#procurement_person').empty();
                    $('#procurement_person').append('<option value="">Select Procurement Person</option>');
                    $.each(json.data, function (key, value)
                    {
                        if (value.order_approval_access == 1 && value.order_approval_access_role == 'procurement_person') {
                            console.log(value.order_approval_access);
                            console.log(value.order_approval_access_role);
                            $procurement_person.append('<option value=' + value.customer_id + ' selected="selected">' + value.email + '</option>'); // return empty
                        } else if ((value.order_approval_access == 0 || value.order_approval_access == null) && (value.order_approval_access_role == null || value.order_approval_access_role == '')) {
                            $procurement_person.append('<option value=' + value.customer_id + '>' + value.email + '</option>'); // return empty
                        }
                    });

                    $('#head_chef').empty();
                    $('#head_chef').append('<option value="">Select Head Chef</option>');
                    $.each(json.data, function (key, value)
                    {
                        if (value.order_approval_access == 1 && value.order_approval_access_role == 'head_chef') {
                            $head_chef.append('<option value=' + value.customer_id + ' selected="selected">' + value.email + '</option>'); // return empty
                        } else if ((value.order_approval_access == 0 || value.order_approval_access == null) && (value.order_approval_access_role == null || value.order_approval_access_role == '')) {
                            $head_chef.append('<option value=' + value.customer_id + '>' + value.email + '</option>'); // return empty
                        }
                    });
                }
            }
        });
    });
    $(document).delegate('#assign_head_chef, #assign_procurement_person', 'click', function (e) {
        e.preventDefault();
        console.log('Hi');
    });

    $(document).delegate('#assign_head_chef, #assign_procurement_person', 'click', function (e) {
        e.preventDefault();
        //alert(this.id);
        console.log($('#head_chef').val());
        console.log($('#procurement_person').val());
        if (this.id == 'assign_head_chef' && $('#head_chef').val() == '') {
            alert('Please select option');
            return false;
        }

        if (this.id == 'assign_procurement_person' && $('#procurement_person').val() == '') {
            alert('Please select option');
            return false;
        }
        $.ajax({
            url: 'index.php?path=account/sub_users/assignorderapprovals',
            type: 'post',
            data: {button: this.id, head_chef: $('#head_chef').val(), procurement_person: $('#procurement_person').val()},
            dataType: 'json',
            success: function (json) {
                console.log(json);
                $.ajax({
                    url: 'index.php?path=account/sub_users/getSubusers',
                    type: 'post',
                    dataType: 'json',
                    success: function (json) {
                        if (json.success == false) {
                            console.log(json.success);
                        }

                        if (json.success == true) {
                            console.log(json.success);
                            console.log(json.data);
                            $(".alerter").show();
                            $('.alerter').delay(5000).fadeOut('slow');

                            var $procurement_person = $('#procurement_person');
                            var $head_chef = $('#head_chef');
                            $('#procurement_person').empty();
                            $('#procurement_person').append('<option value="">Select Procurement Person</option>');
                            $.each(json.data, function (key, value)
                            {
                                if (value.order_approval_access == 1 && value.order_approval_access_role == 'procurement_person') {
                                    $procurement_person.append('<option value=' + value.customer_id + ' selected="selected">' + value.email + '</option>');
                                }// return empty
                                else if ((value.order_approval_access == 0 || value.order_approval_access == null) && (value.order_approval_access_role == '' || value.order_approval_access_role == null)) {
                                    $procurement_person.append('<option value=' + value.customer_id + '>' + value.email + '</option>');
                                }
                            });

                            $('#head_chef').empty();
                            $('#head_chef').append('<option value="">Select Head Chef</option>');
                            $.each(json.data, function (key, value)
                            {
                                if (value.order_approval_access == 1 && value.order_approval_access_role == 'head_chef') {
                                    $head_chef.append('<option value=' + value.customer_id + ' selected="selected">' + value.email + '</option>');
                                }// return empty
                                else if ((value.order_approval_access == 0 || value.order_approval_access == null) && (value.order_approval_access_role == '' || value.order_approval_access_role == null)) {
                                    $head_chef.append('<option value=' + value.customer_id + '>' + value.email + '</option>'); // return empty
                                }
                            });
                        }
                    }
                });
            }
        });
    });
    
    $(document).delegate('#assign_sub_customer_order_approval', 'click', function (e) {
        e.preventDefault();
        console.log('Hi');
        console.log($('#sub_customer_order_approval').val());
        
        $.ajax({
            url: 'index.php?path=account/sub_users/assignsubcustomerorderapproval',
            type: 'post',
            data: { sub_customer_order_approval : $('#sub_customer_order_approval').val() },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                console.log(json.success);
                console.log(json.data);
                $(".alerter").show();
                $('.alerter').delay(5000).fadeOut('slow');
            }
        });
    });
</script>
</body>
</html>
