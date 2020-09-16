<?php //echo '<pre>';print_r($_SESSION);exit;?>
<?php echo $header; ?>
<div class="container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row">
        <div class="col-md-9">
            <ul class="nav nav-tabs">
                <li class="active" style="width:25%;"><a data-toggle="tab" href="#pending">Pending Payments</a></li>
                <li style="width:25%;"><a data-toggle="tab" href="#successfull">Successfull Payments</a></li>
                <li style="width:25%;"><a data-toggle="tab" href="#cancelled">Cancelled Payments</a></li>
                <li style="width:25%;"><a data-toggle="tab" href="#other_payment">Other Payments</a></li>
            </ul>

            <div class="tab-content">
                <div id="pending" class="tab-pane fade in active">
                    <table id="employee" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Id </th>
                                <th>Order Date</th>
                                <th>Amount Payable</th>
                                <th>Payment Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="emp_body">
                        </tbody>
                    </table>
                    <div id="pager">
                        <ul id="paginationpending" class="pagination-sm"></ul>
                    </div>        
                </div>
                <div id="successfull" class="tab-pane fade">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Id </th>
                                <th>Amount Paid</th>
                                <th>Order Date</th>
                                <th>Payment Method</th>
                                <th>Transaction Id</th>
                                <!--<th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="emp_bodys">
                        </tbody>
                    </table>
                    <div id="pager">
                        <ul id="paginationsuccessfull" class="pagination-sm"></ul>
                    </div>     
                </div>
                <div id="cancelled" class="tab-pane fade">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Id </th>
                                <th>Amount Payable</th>
                                <th>Order Date</th>
                                <th>Payment Method</th>
                                <!--<th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="emp_bodysc">
                        </tbody>
                    </table>
                    <div id="pager">
                        <ul id="paginationcancelled" class="pagination-sm"></ul>
                    </div>
                </div>
                <div id="other_payment" class="tab-pane fade">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Customer Id </th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Transaction Id</th>
                                <!--<th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="emp_bodyother">
                        </tbody>
                    </table>
                    <div id="pager">
                        <ul id="paginationother_payment" class="pagination-sm"></ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" id="payment_options">
            Payment Options
            <div class="radio">
                <label><input class="option_pay" onchange="payOptionSelected()"  value="pay_full" type="radio" name="pay_option">Pay Full</label>
            </div>
            <div class="radio">
                <label><input type="radio" class="option_pay" onchange="payOptionSelected()" value="pay_other" name="pay_option">Pay Other Amount</label>
            </div>
        </div>
        <div class="col-md-9" id="payment_options_input" style="display:none;">
            <input id="pesapal_amount" name="pesapal_amount" type="text" value="" class="form-control input-md" required="" placeholder="Enter Amount" minlength="9" maxlength="9" style="display:inline-block; width: 22%;margin-left: 10px;">
            <button type="button" id="button-confirm" data-toggle="collapse" style="width:200px;" class="btn btn-default">PAY &amp; CONFIRM</button>
        </div>

        <input type="hidden" name="order_id" value="<?php echo $this->request->get['order_id'];?>">
        <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id'];?>">
        <input type="hidden" name="total_pending_amount" value="<?php echo $total_pending_amount;?>">
        <input type="hidden" name="pending_order_id" value="<?php echo $pending_order_id;?>">

        <div id="pay-confirm-order" class="col-md-9 confirm_order_class" style="padding:35px;">
            <!--MPESA REMOVED FROM HERE-->
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
                    })(page_category);</script>

<?php } ?>
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
        $("input:radio").removeAttr("checked");
        $("#payment_options_input").hide();
        $('#pay-confirm-order').html('');
        $('input[name="order_id"]').val(orderId);
        $('#mpesa_amount').val(amount_to_pay);
        //$('div#payment_options').hide();
        //$('div#payment_options').focus();
        /* $('html, body').animate({
         scrollTop: $("#payment_options").offset().top
         }, 2000);
         */
        $.ajax({
            url: 'index.php?path=account/transactions/pesapal',
            type: 'post',
            data: {
                order_id: orderId,
                amount: amount_to_pay,
                payment_type: ''
            },
            dataType: 'html',
            cache: false,
            async: false,
            success: function (json) {
                console.log("json");
                console.log(json);
                $('#pay-confirm-order').html(json);
                $('#pay-confirm-order').removeAttr('style');
                return true;
                //window.location = json.redirect;
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                return false;
            }
        });
    }

    function payOptionSelected() {
        //total_pending_amount
        $("#pesapal_amount").prop("readonly", false);
        var radioValue = $("input[name='pay_option']:checked").val();
        var total_pending_amount = $("input[name='total_pending_amount']").val();
        console.log(total_pending_amount);
        $('#pay-confirm-order').html('');
        if (radioValue == 'pay_full') {
            $("#payment_options_input").hide();
            console.log($("input[name=total_pending_amount]").val());
            $.ajax({
                url: 'index.php?path=account/transactions/pesapal',
                type: 'post',
                data: {
                    order_id: $("input[name=pending_order_id]").val(),
                    amount: $("input[name=total_pending_amount]").val(),
                    payment_type: radioValue
                },
                dataType: 'html',
                cache: false,
                async: false,
                success: function (json) {
                    console.log("json");
                    console.log(json);
                    $('#pay-confirm-order').html(json);
                    $('#pay-confirm-order').removeAttr('style');
                    return true;
                    //window.location = json.redirect;
                }, error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    return false;
                }
            });
        } else {
            console.log('Pay Other Amount');
            $('#pay-confirm-order').html('');
            $("#payment_options_input").show();
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('pagination');
        var $pagination = $('#paginationpending'),
                totalRecords = 0,
                records = [],
                displayRecords = [],
                recPerPage = 5,
                page = 1,
                totalPages = 0;
        $.ajax({
            url: "index.php?path=account/transactions/pendingtransactions",
            async: true,
            dataType: 'json',
            success: function (data) {
                records = data.pending_transactions;
                console.log(records);
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }
        });
        function generate_table() {
            var tr;
            $('#emp_body').html('');
            for (var i = 0; i < displayRecords.length; i++) {
                tr = $('<tr/>');
                tr.append("<td>" + displayRecords[i].order_id + "</td>");
                tr.append("<td>" + displayRecords[i].date_added + "</td>");
                tr.append("<td>" + displayRecords[i].total_currency + "</td>");
                tr.append("<td>" + displayRecords[i].payment_method + "</td>");
                tr.append("<a class='btn btn-default' onclick='changeOrderIdForPay(" + displayRecords[i].order_id + "," + displayRecords[i].total + ")'>Pay Now</a>");
                $('#emp_body').append(tr);
            }
        }
        function apply_pagination() {
            $pagination.twbsPagination({
                totalPages: totalPages,
                visiblePages: 6,
                onPageClick: function (event, page) {
                    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
                    endRec = (displayRecordsIndex) + recPerPage;
                    console.log(displayRecordsIndex + 'ssssssssss' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
    $(document).delegate('#send_mail', 'click', function () {
        var checkedNum = $('input[name="app_cand[]"]:checked').length;
        console.log(checkedNum);
        var val = [];
        if (!checkedNum) {
            $(':checkbox:checked').each(function (i) {
                val[i] = $(this).attr("id");
            });
            console.log(val);
        }

        $.ajax({
            url: 'userapi.php',
            type: 'post',
            data: {'app_cand': val},
            dataType: 'json',
            cache: false,
            async: true,
            success: function (json) {
                console.log(json.status);
            }
        });
    });
    $(document).delegate('#button-confirm', 'click', function () {
        console.log('PAY OTHER AMOUNT');
        var radioValue = $("input[name='pay_option']:checked").val();
        $("#pesapal_amount").prop("readonly", true);
        $.ajax({
            url: 'index.php?path=account/transactions/pesapal',
            type: 'post',
            data: {
                order_id: '',
                amount: $("input[name=pesapal_amount]").val(),
                payment_type: radioValue
            },
            dataType: 'html',
            cache: false,
            async: false,
            success: function (json) {
                console.log("json");
                console.log(json);
                $('#pay-confirm-order').html(json);
                $('#pay-confirm-order').removeAttr('style');
                return true;
                //window.location = json.redirect;
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                return false;
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('pagination');
        var $pagination = $('#paginationsuccessfull'),
                totalRecords = 0,
                records = [],
                displayRecords = [],
                recPerPage = 5,
                page = 1,
                totalPages = 0;
        $.ajax({
            url: "index.php?path=account/transactions/pendingtransactions",
            async: true,
            dataType: 'json',
            success: function (data) {
                records = data.success_transactions;
                console.log(records);
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }
        });
        function generate_table() {
            var tr;
            $('#emp_bodys').html('');
            for (var i = 0; i < displayRecords.length; i++) {
                tr = $('<tr/>');
                tr.append("<td>" + displayRecords[i].order_id + "</td>");
                tr.append("<td>" + displayRecords[i].total_currency + "</td>");
                tr.append("<td>" + displayRecords[i].date_added + "</td>");
                tr.append("<td>" + displayRecords[i].payment_method + "</td>");
                tr.append("<td>" + displayRecords[i].transcation_id + "</td>");
                $('#emp_bodys').append(tr);
            }
        }
        function apply_pagination() {
            $pagination.twbsPagination({
                totalPages: totalPages,
                visiblePages: 6,
                onPageClick: function (event, page) {
                    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
                    endRec = (displayRecordsIndex) + recPerPage;
                    console.log(displayRecordsIndex + 'ssssssssss' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
    $(document).delegate('#send_mail', 'click', function () {
        var checkedNum = $('input[name="app_cand[]"]:checked').length;
        console.log(checkedNum);
        var val = [];
        if (!checkedNum) {
            $(':checkbox:checked').each(function (i) {
                val[i] = $(this).attr("id");
            });
            console.log(val);
        }

        $.ajax({
            url: 'userapi.php',
            type: 'post',
            data: {'app_cand': val},
            dataType: 'json',
            cache: false,
            async: true,
            success: function (json) {
                console.log(json.status);
            }
        });
    });</script>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('pagination');
        var $pagination = $('#paginationcancelled'),
                totalRecords = 0,
                records = [],
                displayRecords = [],
                recPerPage = 5,
                page = 1,
                totalPages = 0;
        $.ajax({
            url: "index.php?path=account/transactions/pendingtransactions",
            async: true,
            dataType: 'json',
            success: function (data) {
                records = data.cancelled_transactions;
                console.log(records);
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }
        });
        function generate_table() {
            var tr;
            $('#emp_bodysc').html('');
            for (var i = 0; i < displayRecords.length; i++) {
                tr = $('<tr/>');
                tr.append("<td>" + displayRecords[i].order_id + "</td>");
                tr.append("<td>" + displayRecords[i].total_currency + "</td>");
                tr.append("<td>" + displayRecords[i].date_added + "</td>");
                tr.append("<td>" + displayRecords[i].payment_method + "</td>");
                $('#emp_bodysc').append(tr);
            }
        }
        function apply_pagination() {
            $pagination.twbsPagination({
                totalPages: totalPages,
                visiblePages: 6,
                onPageClick: function (event, page) {
                    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
                    endRec = (displayRecordsIndex) + recPerPage;
                    console.log(displayRecordsIndex + 'ssssssssss' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
    $(document).delegate('#send_mail', 'click', function () {
        var checkedNum = $('input[name="app_cand[]"]:checked').length;
        console.log(checkedNum);
        var val = [];
        if (!checkedNum) {
            $(':checkbox:checked').each(function (i) {
                val[i] = $(this).attr("id");
            });
            console.log(val);
        }

        $.ajax({
            url: 'userapi.php',
            type: 'post',
            data: {'app_cand': val},
            dataType: 'json',
            cache: false,
            async: true,
            success: function (json) {
                console.log(json.status);
            }
        });
    });</script>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('pagination');
        var $pagination = $('#paginationother_payment'),
                totalRecords = 0,
                records = [],
                displayRecords = [],
                recPerPage = 5,
                page = 1,
                totalPages = 0;
        $.ajax({
            url: "index.php?path=account/transactions/pendingtransactions",
            async: true,
            dataType: 'json',
            success: function (data) {
                records = data.success_transactions_pay_other_amount;
                console.log(records);
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }
        });
        function generate_table() {
            var tr;
            $('#emp_bodyother').html('');
            for (var i = 0; i < displayRecords.length; i++) {
                tr = $('<tr/>');
                tr.append("<td>" + displayRecords[i].customer_id + "</td>");
                tr.append("<td>" + displayRecords[i].amount + "</td>");
                tr.append("<td>" + displayRecords[i].created_at + "</td>");
                tr.append("<td>" + displayRecords[i].pesapal_transaction_tracking_id + "</td>");
                $('#emp_bodyother').append(tr);
            }
        }
        function apply_pagination() {
            $pagination.twbsPagination({
                totalPages: totalPages,
                visiblePages: 6,
                onPageClick: function (event, page) {
                    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
                    endRec = (displayRecordsIndex) + recPerPage;
                    console.log(displayRecordsIndex + 'ssssssssss' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
</script>

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
</style>
</body>
</html>