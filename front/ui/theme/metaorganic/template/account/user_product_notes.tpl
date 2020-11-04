<?php echo $header; ?>
<div class="container">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row">
        <div class="col-md-9 nopl">
            <div class="dashboard-address-content">
                <div class="row">
                    <div class="col-md-9"><h2>User Product Notes</h2> <br></div>
                    <div class="col-md-3"><a href="#" type="button" class="btn-link text_green" data-toggle="modal" data-target="#addressModal"><i class="fa fa-plus-circle"></i> Add new user notes</a></div>
                </div>
                <div id="pending">
                    <table id="employee" class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="order_id">Product Id </th>
                                <th class="order_id">Product Store Id</th>
                                <th class="order_id">Product Name</th>
                                <th class="order_id">Product Notes</th>
                                <th class="order_id">Action</th>
                            </tr>
                        </thead>
                        <tbody id="emp_body">
                        </tbody>
                    </table>
                    <div id="pager">
                        <ul id="paginationpending" class="pagination-sm"></ul>
                    </div>        
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

<div class="addressModal">
        <div class="modal fade" id="addressModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Add new user product notes</h2>
                            </div>
                            <div id="address-message" class="col-md-12" style="color: red">
                            </div>
                            <div id="address-success-message" style="color: green">
                            </div>
                            <div class="addnews-address-form">
                                    <!-- Multiple Radios (inline) -->
                                <form id="new-address-form">
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="name">Choose Product</label>
                                            <select id="modal_product_name" name="modal_product_name" class="form-control input-md" required=""></select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="name">Product Note</label>
                                            <textarea name="modal_product_note" id="modal_product_note" class="form-control input-md" required=""></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button id="singlebutton" name="singlebutton" type="button" class="btn btn-primary">SAVE</button>
                                            <button type="button" class="btn btn-grey cancelbut" data-dismiss="modal">CLOSE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    var page_category = 'my-product-notes-page';
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
<script>
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
            url: "index.php?path=account/user_product_notes/getUserProductNotes",
            async: true,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                records = data.user_product_notes;
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
                tr.append("<td><input type='checkbox' id='product_store_id_selected' data-id='" + displayRecords[i].product_store_id + "' data-amount='" + displayRecords[i].value + "' name='product_store_selected' value='" + displayRecords[i].product_store_id + "'></td>");
                tr.append("<td class='product_id'>" + displayRecords[i].product_id + "</td>");
                tr.append("<td>" + displayRecords[i].product_store_id + "</td>");
                tr.append("<td class='amount'>" + displayRecords[i].name + "</td>");
                tr.append("<td>" + displayRecords[i].product_note + "</td>");
                tr.append("<td><a class='btn btn-default' onclick='changeOrderIdForPay(" + displayRecords[i].product_store_id + "," + displayRecords[i].value + ")'> <i class='fa fa-edit'></i> </a>\n\
    <a class='btn btn-default' onclick='changeOrderIdForPay(" + displayRecords[i].product_store_id + "," + displayRecords[i].value + ")'> <i class='fa fa-trash'></i> </a></td>");
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

</script>
<?php if($redirect_coming) { ?>
<script type="text/javascript">
    $('#save-button').click();
</script>

<?php } ?>
</body>
</html>