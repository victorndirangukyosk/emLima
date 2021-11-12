<button type="button" id="button-pezesha-confirm" data-toggle="collapse" data-loading-text="<?= $text_loading ?>" class="btn btn-default"><?= $button_confirm?></button>

<script type="text/javascript"><!--
$('#button-pezesha-confirm').on('click', function() {
    
    //location = '<?php echo $continue; ?>';

    
    $('#loading').show();

    if (!saveAddress()) { 
        return false; 
    }
    


    $.ajax({
        type: 'get',
        url: 'index.php?path=payment/pezesha/applyloan',
        dataType: 'json',
        cache: false,
        beforeSend: function() {
                $(".overlayed").show();
                $('#button-confirm').button('loading');
        },
        complete: function() {
                $(".overlayed").hide();
                $('#button-confirm').button('reset');
                $('#loading').hide();
        },      
        success: function(json) {
           console.log(json); 
           if(json.status) {
           location = '<?php echo $continue; ?>';
           }
           
           if(!json.status) {
           console.log(json);     
           }

        }       
    });
});
//--></script> 
