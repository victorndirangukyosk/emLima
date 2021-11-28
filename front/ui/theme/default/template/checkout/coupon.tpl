<form action="" id="coupon-form">
    <input type="text" maxlength="10" placeholder="Enter your coupon code" name="coupon">
    <button data-style="zoom-out" type="button" class="ladda-button" id="button-coupon">
        ADD
    </button>
</form>
<h5 style="display: none;" id="coupon-success"></h5>

<script type="text/javascript"><!--
$('#button-coupon').on('click', function() {
    $.ajax({
        url: 'index.php?path=checkout/coupon/coupon',
        type: 'post',
        data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
        dataType: 'json',
        beforeSend: function() {
            $('#button-coupon').button('loading');
        },
        complete: function() {
            $('#button-coupon').button('reset');
        },
        success: function(json) {
            $('.alert').remove();
            $('#error').remove();

            if (json['error']) {
                $('#button-coupon').after('<p id="error">Invalid Coupon</p>');
            }

            if (json['redirect']) {
                location = json['redirect'];
            }
        }
    });
});
//--></script>
