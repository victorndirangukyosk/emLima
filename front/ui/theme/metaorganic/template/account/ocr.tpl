<?php echo $header; ?>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
    integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g=="
    crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
    integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
    crossorigin="anonymous"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" enctype="multipart/form-data" id="po_form">
                <input type="hidden" name="customer_id" value=<?= $customer_id ?>>
                <input type="hidden" name="customer_category" value=<?= $customer_category ?>>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="delivery_address">Delivery Address</label>
                        <select id="delivery_address" name="delivery_address" class="form-control" required>
                            <?php foreach($addresses as $address_id => $address) { ?>
                            <option value=<?=$address_id ?>>
                                <?= $address ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="delivery_time">Delivery Date & Time</label>
                        <input id="delivery_time" name="delivery_time" class="form-control" type="text"
                            placeholder="Click to enter date and time" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="po">Purchase Order Document</label>
                        <input id="po" name="po" class="form-control" type="file" accept=".png,.jpg, .jpeg" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input type="submit" id="submit" name="sumbit" class="btn" value="Process Order">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    jQuery('#delivery_time').datetimepicker({
        format: 'd.m.Y H:i',
        minDate: 0,
        minTime: '08:00',
        maxTime: '16:00'
    });

    $(function () {
        $('#po_form').on('submit', function (e) {
            e.preventDefault();
            
            var data = new FormData();
            $.each($("#po_form").serializeArray(), function(key, input) {
                data.append(input.name, input.value);
            });
            data.append('document', ($('input[name="po"]')[0].files[0]));

            $.ajax({
                url: "http://localhost:3000/v1/ocr/po",
                type: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>