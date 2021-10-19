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
                                <th></th>
                                <th class="order_id">Order Id </th>
                                <th class="order_id">Order Date</th>
                                <th class="order_id">Order Total</th>
                                <th class="order_id">Amount Payable</th>
                                <th class="order_id">Payment Method</th>
                                <!--<th class="order_id">Action</th>-->
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
                                <th class="order_id">Order Id </th>
                                <th class="order_id">Amount Paid</th>
                                <th class="order_id">Order Date</th>
                                <th class="order_id">Payment Method</th>
                                <th class="order_id">Transaction Id</th>
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
                                <th class="order_id">Order Id </th>
                                <th class="order_id">Amount Payable</th>
                                <th class="order_id">Order Date</th>
                                <th class="order_id">Payment Method</th>
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
                                <th class="order_id">Customer Id </th>
                                <th class="order_id">Amount</th>
                                <th class="order_id">Date</th>
                                <th class="order_id">Transaction Id</th>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="radio">
                        <label><input class="option_pay" onchange="showPayWith()"  value="pay_full" type="radio" name="pay_option">Pay Full</label>
                    </div>
                </div>
                <!--<div class="radio">
                    <label><input type="radio" class="option_pay" onchange="showPayWith()" value="pay_other" name="pay_option">Pay Other Amount</label>
                </div>-->
                <div class="col-md-6">
                    <div class="radio">
                        <label><input type="radio" class="option_pay" onchange="showPayWith()" value="pay_selected_order" name="pay_option">Pay Selected Orders</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9" id="pay_with" style="display:none;">
            Pay With
            <div class="row">
                <div class="col-md-6">
                    <div class="radio">
                        <label><input class="option_pay" onchange="payOptionSelected()" type="radio" name="pay_with">PesaPal</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="radio">
                        <label><input class="option_pay" onchange="payWithmPesa()" type="radio" name="pay_with">mPesa Online</label>
                    </div>
                </div>
                <!--<div class="col-md-6">
                    <div class="radio">
                        <label><input class="option_pay" onchange="LoadInterSwitch()" type="radio" name="pay_with">Interswitch</label>
                    </div>
                </div>//-->
            </div>
        </div>
        
        <div class="col-md-9" id="payment_options_input" style="display:none;">
            <input id="pesapal_amount" name="pesapal_amount" type="number" min="0.01" step="0.01" value="" class="form-control input-md" required="" placeholder="Enter Amount" minlength="9" maxlength="9" style="display:inline-block; width: 22%;margin-left: 10px;">
            <button type="button" id="button-confirm" data-toggle="collapse" style="width:200px;" class="btn btn-default">PAY &amp; CONFIRM</button>
        </div>

        <input type="hidden" name="order_id" value="<?php echo $this->request->get['order_id'];?>">
        <input type="hidden" name="customer_id" value="<?php echo $_SESSION['customer_id'];?>">
        <input type="hidden" name="total_pending_amount" value="<?php echo $total_pending_amount;?>">
        <input type="hidden" name="pending_order_id" value="<?php echo $pending_order_id;?>">
        <input type="hidden" name="mpesa_checkout_request_id" id="mpesa_checkout_request_id" value="">

        <div id="pay-confirm-order" class="col-md-9 confirm_order_class" style="padding:35px;">
            <!--MPESA REMOVED FROM HERE-->
        </div>
        
        <div id="pay-confirm-order-mpesa" class="col-md-9 confirm_order_class" style="display:none; padding:35px;">
            <p>mPesa Online</p>
            <div class="row">
                <div class="col-md-9">
                    <span class="input-group-btn" style="padding-bottom: 10px;">
                        <p id="button-reward" class="" style="padding: 13px 14px;    margin-top: -9px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">
                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">+254</font></font></font></font>
                        </p>

                        <input id="mpesa_phone_number" name="telephone" type="text" value="<?php echo $this->customer->getTelephone(); ?>" class="form-control input-md" required="" placeholder="Mobile number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" style="display: inline-block;    width: 50%;" >

                    </span>
                </div>
                <div class="col-md-3">
                    <button type="button" id="mpesa-button-confirm" data-toggle="collapse" data-loading-text="checking phone..." class="btn btn-default">PAY &amp; CONFIRM</button>
                    
                    <button type="button" id="button-retry" class="btn btn-default"> Retry</button>

                    <button type="button" id="button-complete" data-toggle="collapse" data-loading-text="checking payment..." class="btn btn-default">Confirm Payment</button>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-danger" id="error_msg" style="margin-bottom: 7px;">
                    </div>
                    <div class="alert alert-success" style="font-size: 14px;" id="success_msg" style="margin-bottom: 7px;">
                    </div>
                </div>    
            </div>
            <!--MPESA REMOVED FROM HERE-->
        </div>
        
        <div id="pay-confirm-order-interswitch" class="col-md-9 confirm_order_class" style="display:none; padding:35px;">
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
                $("#pay-confirm-order").prepend("<p>* 3.5% Payment Gateway Charges Applicable On Order Total</p>");
                $('#pay-confirm-order').removeAttr('style');
                return true;
                //window.location = json.redirect;
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                return false;
            }
        });
    }
    
    function changeOrderIdForPays(orderId, amount_to_pay) {
    if($('input[type="checkbox"][data-id="'+orderId+'"]').prop('checked') == false) {
    $('input[type="checkbox"][data-id="'+orderId+'"]').prop('checked', true).trigger('change');
    return false;
    }
    
    if($('input[type="checkbox"][data-id="'+orderId+'"]').prop('checked') == true) {  
    $('input[type="checkbox"][data-id="'+orderId+'"]').prop('checked', false).trigger('change');
    return false;
    }
    
    }
    
    function payOptionSelected() {
        //total_pending_amount
        $("#pesapal_amount").prop("readonly", false);
        var radioValue = $("input[name='pay_option']:checked").val();
        var total_pending_amount = $("input[name='total_pending_amount']").val();
        console.log(total_pending_amount);
        $('#pay-confirm-order').html('');
        if (radioValue == 'pay_full') {
            $("#pay-confirm-order-mpesa").hide();
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
                beforeSend: function () {
                $('#pay-confirm-order').html('Loading Please Wait....');
                },
                complete: function () {
                },
                success: function (json) {
                    console.log("json");
                    console.log(json);
                    $('#pay-confirm-order').html(json);
                    $("#pay-confirm-order").prepend("<p>* 3.5% Payment Gateway Charges Applicable On Order Total</p>");
                    $('#pay-confirm-order').removeAttr('style');
                    return true;
                    //window.location = json.redirect;
                }, error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    return false;
                }
            });
        } else if (radioValue == 'pay_selected_order') {
            $("#pay-confirm-order-mpesa").hide();
            var checkedNum = $('input[name="order_id_selected[]"]:checked').length;
            console.log(checkedNum);
            var val = [];
            var amount = [];
            if (!checkedNum) {
                $(':checkbox:checked').each(function (i) {
                    val[i] = $(this).data("id");
                    amount[i] = $(this).data("amount");
                });
                console.log(val);
                console.log(amount);
                var total = 0;
                for (var i = 0; i < amount.length; i++) {
                    total += amount[i] << 0;
                }
                console.log(total);
            }
            if (val.length == 0 || amount.length == 0) {
                $("input:radio").removeAttr("checked");
                alert('Please select atleast one order!');
                return false;
            }
            $.ajax({
                url: 'index.php?path=account/transactions/pesapal',
                type: 'post',
                data: {
                    order_id: val,
                    amount: total,
                    payment_type: radioValue
                },
                dataType: 'html',
                cache: false,
                async: false,
                beforeSend: function () {
                $('#pay-confirm-order').html('Loading Please Wait....');
                },
                complete: function () {
                },
                success: function (json) {
                    console.log("json");
                    console.log(json);
                    $('#pay-confirm-order').html(json);
                    $("#pay-confirm-order").prepend("<p>* 3.5% Payment Gateway Charges Applicable On Order Total</p>");
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
    
    function payWithmPesa() {
        $("#pay-confirm-order").html('');
        $("#pay-confirm-order").hide();
        $("#pay-confirm-order-mpesa").show();
    }
    
    function payWithInterswitch() {
            var radioValue = $("input[name='pay_option']:checked").val();
            var total_pending_amount = $("input[name='total_pending_amount']").val();
            console.log(total_pending_amount);
            
            if (radioValue == 'pay_full') {
                
            var val = $("input[name=pending_order_id]").val();
            var total = $("input[name=total_pending_amount]").val();
            
            } else if (radioValue == 'pay_selected_order') {
                
            var checkedNum = $('input[name="order_id_selected[]"]:checked').length;
            console.log(checkedNum);
            var val = [];
            var amount = [];
            if (!checkedNum) {
                $(':checkbox:checked').each(function (i) {
                    val[i] = $(this).data("id");
                    amount[i] = $(this).data("amount");
                });
                console.log(val);
                console.log(amount);
                var total = 0;
                for (var i = 0; i < amount.length; i++) {
                    total += amount[i] << 0;
                }
                console.log(total);
            }
            if (val.length == 0 || amount.length == 0) {
                $("input:radio").removeAttr("checked");
                alert('Please select atleast one order!');
                return false;
            }
            }    
            
            $.ajax({
                url: 'index.php?path=account/transactions/interswitch',
                type: 'post',
                data: {
                    order_id: val,
                    amount: total,
                    payment_type: radioValue
                },
                dataType: 'html',
                cache: false,
                async: false,
                beforeSend: function () {
                $('#pay-confirm-order-interswitch').html('Loading Please Wait....');
                },
                complete: function () {
                },
                success: function (html) {
                    console.log(html);
                    $('#pay-confirm-order-interswitch').html(html);
                    return true;
                    //window.location = json.redirect;
                }, error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    return false;
                }
            });
    }
    
function LoadInterSwitch() {
$("#pay-confirm-order").html('');
$("#pay-confirm-order").hide();
$("#pay-confirm-order-mpesa").hide();
submitHandler(event);
}
</script>
<script type="text/javascript">
function showPayWith() {
    payWithInterswitch();
    $('#pay-confirm-order').html('');
    $('#pay-confirm-order-mpesa').hide();
    $('input[name="pay_with"]:checked').removeAttr('checked');
    $('#pay_with').hide();
    var radioValue = $("input[name='pay_option']:checked").val();
    if (radioValue == 'pay_selected_order') {
            var checkedNum = $('input[name="order_id_selected[]"]:checked').length;
            console.log(checkedNum);
            var val = [];
            var amount = [];
            if (!checkedNum) {
                $(':checkbox:checked').each(function (i) {
                    val[i] = $(this).data("id");
                    amount[i] = $(this).data("amount");
                });
                console.log(val);
                console.log(amount);
                var total = 0;
                for (var i = 0; i < amount.length; i++) {
                    total += amount[i] << 0;
                }
                console.log(total);
            }
            if (val.length == 0 || amount.length == 0) {
                $("#pay_with").hide();
                $("input:radio").removeAttr("checked");
                alert('Please select atleast one order!');
                return false;
            }else {
            $("#pay_with").show(); 
            }
    } else if(radioValue == 'pay_full') {
      $("#pay_with").show(); 
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
                tr.append("<td><input type='checkbox' id='order_id_selected' data-id='" + displayRecords[i].order_id + "' data-amount='" + displayRecords[i].pending_amount + "' name='order_id_selected' value='" + displayRecords[i].order_id + "'></td>");
                tr.append("<td class='order_id'>" + displayRecords[i].order_id + "</td>");
                tr.append("<td>" + displayRecords[i].date_added + "</td>");
                tr.append("<td class='amount'>" + displayRecords[i].total_currency + "</td>");
                tr.append("<td class='amount'>" + displayRecords[i].pending_amount_currency + "</td>");
                tr.append("<td>" + displayRecords[i].payment_method + "</td>");
                <!--tr.append("<td><a class='btn btn-default' onclick='changeOrderIdForPays(" + displayRecords[i].order_id + "," + displayRecords[i].pending_amount + ")'>Pay Now</a></td>");//-->
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
                    console.log(displayRecordsIndex + 'PAGINATION' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });

    /*$(document).delegate('#button-confirm', 'click', function () {
        console.log('PAY OTHER AMOUNT');
        var amount = $("#pesapal_amount").val();
        var radioValue = $("input[name='pay_option']:checked").val();
        var validatePrice = function (amount) {
            return /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(amount);
        }
        var result = validatePrice(amount); // False
        console.log(result);
        if (result == false) {
            alert('Please enter valid amount');
            return false;
        }
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
                $("#pay-confirm-order").prepend("<p>* 3.5% Payment Gateway Charges Applicable On Order Total</p>");
                $('#pay-confirm-order').removeAttr('style');
                return true;
                //window.location = json.redirect;
            }, error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                return false;
            }
        });
    });*/
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
                tr.append("<td class='order_id'>" + displayRecords[i].order_id + "</td>");
                tr.append("<td class='amount'>" + displayRecords[i].total_currency + "</td>");
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
                    console.log(displayRecordsIndex + 'PAGINATION' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
    </script>

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
                tr.append("<td class='order_id'>" + displayRecords[i].order_id + "</td>");
                tr.append("<td class='amount'>" + displayRecords[i].total_currency + "</td>");
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
                    console.log(displayRecordsIndex + 'PAGINATION' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
    $(document).delegate('input[name="order_id_selected"]', 'click', function () {
        $('#pay_with').hide();
        $("#pay-confirm-order").html('');
        $("#pay-confirm-order").hide();
        $("#pay-confirm-order-mpesa").hide();
        var checkedNum = $('input[name="order_id_selected[]"]:checked').length;
        console.log(checkedNum);
        var val = [];
        if (!checkedNum) {
            $(':checkbox:checked').each(function (i) {
                val[i] = $(this).data("id");
            });
            console.log(val.length);
        }
        if (val.length > 0) {
            $("input:radio").removeAttr("checked");
            $('#pay-confirm-order').html('');
        }
    });
    </script>

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
                tr.append("<td class='order_id'>" + displayRecords[i].customer_id + "</td>");
                tr.append("<td class='amount'> KES " + displayRecords[i].amount + "</td>");
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
                    console.log(displayRecordsIndex + 'PAGINATION' + endRec);
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    generate_table();
                }
            });
        }
    });
</script>
<script type="text/javascript">

        $('#error_msg').hide();
        $('#success_msg').hide();
        $('#button-complete').hide();
        $('#button-retry').hide();
	
        $( document ).ready(function() {
            console.log("referfxx def");
            if($('#mpesa_phone_number').val().length >= 9) {
                $( "#mpesa-button-confirm" ).prop( "disabled", false );
                $( "#button-retry" ).prop( "disabled", false );
            } else {
                $( "#mpesa-button-confirm" ).prop( "disabled", true );
                $( "#button-retry" ).prop( "disabled", true );
            }
        });

        $('#mpesa_phone_number').on('input', function() { 
            console.log("referfxx");
            if($(this).val().length >= 9) {
                $( "#mpesa-button-confirm" ).prop( "disabled", false );
                $( "#button-retry" ).prop( "disabled", false );
            } else {
                $( "#mpesa-button-confirm" ).prop( "disabled", true );
                $( "#button-retry" ).prop( "disabled", true );
            }
        });

        $('#mpesa-button-confirm,#button-retry').on('click', function() {
	    
            $('#loading').show();

            $('#error_msg').hide();
            
            var radioValue = $("input[name='pay_option']:checked").val();
            var total_pending_amount = $("input[name='total_pending_amount']").val();
            console.log(total_pending_amount);
            
            if (radioValue == 'pay_full') {
                
            var val = $("input[name=pending_order_id]").val();
            var total = $("input[name=total_pending_amount]").val();
            
            } else if (radioValue == 'pay_selected_order') {
                
            var checkedNum = $('input[name="order_id_selected[]"]:checked').length;
            console.log(checkedNum);
            var val = [];
            var amount = [];
            if (!checkedNum) {
                $(':checkbox:checked').each(function (i) {
                    val[i] = $(this).data("id");
                    amount[i] = $(this).data("amount");
                });
                console.log(val);
                console.log(amount);
                var total = 0;
                for (var i = 0; i < amount.length; i++) {
                    total += amount[i] << 0;
                }
                console.log(total);
            }
            if (val.length == 0 || amount.length == 0) {
                $("input:radio").removeAttr("checked");
                alert('Please select atleast one order!');
                return false;
            }
            }

            if($('#mpesa_phone_number').val().length >= 9) {
                $.ajax({
                        type: 'post',
                        url: 'index.php?path=payment/mpesa/confirmtransaction',
                        data: { 
                        mobile : encodeURIComponent($('#mpesa_phone_number').val()),
                        order_id: val,
                        amount: total,
                        payment_type: radioValue,
                        payment_method : 'mpesa'
                        },
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                            $(".overlayed").show();
                            $('#mpesa-button-confirm').button('loading');
                        },
                        complete: function() {
                            $(".overlayed").hide();
                        },      
                        success: function(json) {

                                console.log(json);
                                console.log('json mpesa');

                                $('#mpesa-button-confirm').button('reset');
                            $('#loading').hide();

                                if(json['processed']) {
                                        //location = '<?php echo $continue; ?>';
		        		
                                        //$('#success_msg').html('A payment request has been sent to the mpesa number '+$('#mpesa_phone_number').val()+'. Please wait for a few seconds then check for your phone for an MPESA PIN entry prompt.');

                                        $('#success_msg').html('A payment request has been sent on your above number. Please make the payment by entering mpesa PIN and click on Confirm Payment button after receiving sms from mpesa');
		        		$('#mpesa_checkout_request_id').val(json['response'].CheckoutRequestID);
                                        $('#success_msg').show();
		        		
                                        $('#button-complete').show();

                                        console.log('json mpesa1');
                                        $('#mpesa-button-confirm').hide();
                                        $('#button-retry').hide();
                                        console.log('json mpesa2');

                                } else {
                                        console.log('json mpesa err');
                                        console.log(json['error']);
                                        $('#error_msg').html(json['error']);
                                        $('#error_msg').show();
                                }
		            
                        },
                        error: function(json) {

                                console.log('josn mpesa');
                                console.log(json);

                                $('#error_msg').html(json['responseText']);
                                $('#error_msg').show();
                        }
                    });
            }
        });

        $('#button-complete').on('click', function() {
	    
            $('#error_msg').hide();
            $('#success_msg').hide();
        
            var radioValue = $("input[name='pay_option']:checked").val();
            var total_pending_amount = $("input[name='total_pending_amount']").val();
            console.log(total_pending_amount);
            
            if (radioValue == 'pay_full') {
                
            var val = $("input[name=pending_order_id]").val();
            var total = $("input[name=total_pending_amount]").val();
            
            } else if (radioValue == 'pay_selected_order') {
                
            var checkedNum = $('input[name="order_id_selected[]"]:checked').length;
            console.log(checkedNum);
            var val = [];
            var amount = [];
            if (!checkedNum) {
                $(':checkbox:checked').each(function (i) {
                    val[i] = $(this).data("id");
                    amount[i] = $(this).data("amount");
                });
                console.log(val);
                console.log(amount);
                var total = 0;
                for (var i = 0; i < amount.length; i++) {
                    total += amount[i] << 0;
                }
                console.log(total);
            }
            if (val.length == 0 || amount.length == 0) {
                $("input:radio").removeAttr("checked");
                alert('Please select atleast one order!');
                return false;
            }
            }

        $.ajax({
                type: 'post',
                url: 'index.php?path=payment/mpesa/completetransaction',
            dataType: 'json',
                cache: false,
                data: { 
                        mobile : encodeURIComponent($('#mpesa_phone_number').val()),
                        order_id: val,
                        amount: total,
                        payment_type: radioValue,
                        payment_method : 'mpesa'
                        },
                beforeSend: function() {
                    $(".overlayed").show();
                    $('#button-complete').button('loading');
                },
                complete: function() {
                    $(".overlayed").hide();
                    $('#button-complete').button('reset');
                },      
                success: function(json) {

                        console.log(json);
                        console.log('json mpesa');
                        if(json['status']) {
                                //success
                                $('#success_msg').html('Payment Successfull. Wait Until Page Refresh!');
                                $('#success_msg').show();
                                setInterval(function(){ window.location.replace(json['redirect']); }, 10000);
                        } else {

                                //failed
                                //$('#mpesa-button-confirm').show();
                                //$('#button-retry').hide();
                                //$('#button-complete').hide();

                                $('#error_msg').html(json['error']);
                                $('#error_msg').show();

                                $('#button-complete').hide();
                                $('#button-retry').show();

                        }
	            
                },
                error: function(json) {
                        $('#error_msg').html(json['responseText']);
                        $('#error_msg').show();
                }
            });
        });
</script>
<script type="text/javascript">
$( document ).ready(function() { setInterval(function(){ mpesaresponse(); }, 30000 ); });
function mpesaresponse() {
                if($('#mpesa_checkout_request_id').val() != '') {
                $.ajax({
                        type: 'post',
                        url: 'index.php?path=payment/mpesa/mpesaautoupdate',
                        data: { 
                        mpesa_checkout_request_id : encodeURIComponent($('#mpesa_checkout_request_id').val()),
                        },
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        $(".overlayed").show();
                        $('#mpesa-button-confirm').button('loading');
                        },
                        complete: function() {
                        $(".overlayed").hide();
                        },       
                        success: function(json) {
                        if(json['processed'] == true) {
                        $('#mpesa_checkout_request_id').val('');
                        $('#success_msg').html('Payment Successfull. Wait Until Page Refresh!');
                        $('#success_msg').show();
                        setInterval(function(){ window.location.replace(json['redirect']); }, 10000);
                        return false;
                        } 
                        if(json['processed'] == false) {
                        $('#mpesa_checkout_request_id').val('');
                        $('#success_msg').html('');
                        $('#success_msg').hide();
                        $('#error_msg').html(json['mpesa_payments_response'].description);
                        $('#error_msg').show();
                        $('#button-complete').hide();
                        $('#button-retry').show();
                        return false;
                        }
                        if(json['processed'] == '') {
                        $('#mpesa_checkout_request_id').val('');
                        $('#button-complete').show();
                        $('#mpesa-button-confirm').hide();
                        $('#button-retry').hide();
                        $('#loading').hide();
                        return false;
                        }
                        },
                        error: function(json) {
                        console.log(json);
                        }
                });
                }                
}
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