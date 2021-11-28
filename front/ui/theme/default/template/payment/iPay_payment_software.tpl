<!-- <form action="<?php echo $url; ?>" method="post">
    <div class="buttons">
        <div class="pull-rightx">
            <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
        </div>
    </div>
</form> -->

<button type="button" id="button-confirm" data-toggle="collapse" data-loading-text="<?= $text_loading ?>" class="btn btn-default">PAY &amp; CONFIRM</button>

<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
    
    //location = '<?php echo $continue; ?>';

    
    $('#loading').show();

    location = '<?php echo $url; ?>';
});
//--></script> 

