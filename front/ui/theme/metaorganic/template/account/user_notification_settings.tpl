<?php echo $header; ?>
<div class="container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row">
        <div class="col-md-9 nopl">
            <div class="dashboard-address-content">
                <div class="row">
                    <div class="col-md-9"><h2>Notification Settings</h2> <br></div>
                </div>
                
                <div id="pending">
                    <table id="employee" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="order_id">Notification Name</th>
                                <th class="order_id">Status</th>
                                <th class="order_id">Action</th>
                            </tr>
                        </thead>
                        <tbody id="emp_body">
                            <tr>
                                <td>Sms Notification</td>
                                <td class="smsstatus<?php echo $customer_info['customer_id']; ?>"><?php if($customer_info['sms_notification'] == 0) { echo "Disabled"; }?> <?php if($customer_info['sms_notification'] == 1) { echo "Enabled"; }?></td>
                                <td><?php if($customer_info['sms_notification'] == 0) { ?><a data-confirm="Enable Sms Notification!" class="btn btn-success useractivate" data-active="1" data-notification-id="sms" data-store-id="<?php echo $customer_info['customer_id']; ?>" data-toggle="tooltip" title="Enable Sms Notification"><i class="fa fa-check"></i></a><?php } ?>
                                <?php if($customer_info['sms_notification'] == 1) { ?><a data-confirm="Disable Sms Notification!" class="btn btn-success useractivate" data-active="0" data-notification-id="sms" data-store-id="<?php echo $customer_info['customer_id']; ?>" data-toggle="tooltip" title="Disable Sms Notification"><i class="fa fa-times"></i></a><?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Mobile Notification</td>
                                <td class="mobilestatus<?php echo $customer_info['customer_id']; ?>"><?php if($customer_info['mobile_notification'] == 0) { echo "Disabled"; }?> <?php if($customer_info['mobile_notification'] == 1) { echo "Enabled"; }?></td>
                                <td><?php if($customer_info['mobile_notification'] == 0) { ?><a data-confirm="Enable Mobile Notification!" class="btn btn-success useractivate" data-notification-id="mobile" data-active="1" data-store-id="<?php echo $customer_info['customer_id']; ?>" data-toggle="tooltip" title="Enable Mobile Notification"><i class="fa fa-check"></i></a><?php } ?>
                                <?php if($customer_info['mobile_notification'] == 1) { ?><a data-confirm="Disable Mobile Notification!" class="btn btn-success useractivate" data-notification-id="mobile" data-active="0" data-store-id="<?php echo $customer_info['customer_id']; ?>" data-toggle="tooltip" title="Disable Mobile Notification"><i class="fa fa-times"></i></a><?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Email Notification</td>
                                <td class="emailstatus<?php echo $customer_info['customer_id']; ?>"><?php if($customer_info['email_notification'] == 0) { echo "Disabled"; }?> <?php if($customer_info['email_notification'] == 1) { echo "Enabled"; }?></td>
                                <td><?php if($customer_info['email_notification'] == 0) { ?><a data-confirm="Enable Email Notification!" class="btn btn-success useractivate" data-notification-id="email" data-active="1" data-store-id="<?php echo $customer_info['customer_id']; ?>" data-toggle="tooltip" title="Enable Email Notification"><i class="fa fa-check"></i></a><?php } ?>
                                <?php if($customer_info['email_notification'] == 1) { ?><a data-confirm="Disable Email Notification!" class="btn btn-success useractivate" data-notification-id="email" data-active="0" data-store-id="<?php echo $customer_info['customer_id']; ?>" data-toggle="tooltip" title="Disable Email Notification"><i class="fa fa-times"></i></a><?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12"></div>
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
    var page_category = 'my-notification-settings-page';
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
<?php if($redirect_coming) { ?>
<script type="text/javascript">
    $('#save-button').click();
</script>
<?php } ?>
<script>
    $(document).delegate('.useractivate', 'click', function () {
        var choice = confirm($(this).attr('data-confirm'));
        if (choice) {
            console.log('Activate!');
            var user_id = $(this).attr('data-store-id');
            var notification_id = $(this).attr('data-notification-id');
            var active_status = $(this).attr('data-active');
            console.log(user_id);
            console.log(notification_id);
            console.log(active_status);
            
            if (active_status == 0) {
                $(this).find('i').toggleClass('fa-times fa-check');
                console.log(active_status + ' ' + 'Active Status');
                $(this).attr('data-confirm', 'Enable ' +notification_id+' Notifications!');
                $(this).attr('data-active', '1');
                $(this).attr('title', 'Enable ' +notification_id+' Notifications!');
                $('.'+notification_id+'status' + user_id).html('Disabled');
            }

            if (active_status == 1) {
                $(this).find('i').toggleClass('fa-check fa-times');
                console.log(active_status + ' ' + 'Active Status');
                $(this).attr('data-confirm', 'Disable '+notification_id+' Notifications!');
                $(this).attr('data-active', '0');
                $(this).attr('title', 'Disable '+notification_id+' Notifications!');
                $('.'+notification_id+'status' + user_id).html('Enabled');
            }

            $.ajax({
                url: 'index.php?path=account/user_notification_settings/CustomerNotifications',
                type: 'post',
                data: {user_id: user_id, active_status: active_status, notification_id: notification_id},
                dataType: 'json',
                success: function (json) {
                    console.log(json);
                    if (active_status == 0) {
                        $(this).find('i').toggleClass('fa-times fa-check');
                        console.log(active_status + ' ' + 'Active Status');
                        $('.status' + user_id).html('Disabled');
                    }

                    if (active_status == 1) {
                        $(this).find('i').toggleClass('fa-times fa-check');
                        console.log(active_status + ' ' + 'Active Status');
                        $('.status' + user_id).html('Enabled');
                    }

                }
            });
        }
    });
</script>
</body>
</html>