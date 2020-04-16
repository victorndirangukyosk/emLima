<?php echo $header; ?>

<div class="page-container">
    <div class="session-controller">
        <div class="topspace"></div>
        <div class="containerliquid whitebg" style="min-height: 300px;">
            <div class="container">
                <div class="row">
                    
                    <br />
                    <br />
                    <br />
                    
                    <div class="aligntocenter col-md-12">
                        <h1 class="signupheading"><?php echo $heading_title; ?></h3>
                    </div>

                    <br />
                    <br />
                    <br />
                    <br />
                    
                    <div style="font-size: 16px;" class="col-md-10">
                        <?php echo html_entity_decode($text_message);  ?>
                    </div>
                    
                    <br />
                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />
                    
                    
                    <br />
                    <br />
                    <br />
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>

<script type="text/javascript">

<?php if(isset($redirect_url_return)) { ?>
    $(function(){

        console.log("Ce");
        setTimeout(function () {
            if('<?php echo !is_null($redirect_url) ?>') {

                //alert('index.php?path=account/order/info&order_id=' + <?= $order_id ?>);
                //console.log('<?= urlencode($redirect_url)?>');

                console.log("erf");
                console.log("<?= $redirect_url ?>");
                //location = 'index.php?path=account/order/info&order_id=' + <?= $order_id ?>;    
                location = '<?= $redirect_url ?>';    
                //location = '<?= $redirect_url ?>';    
            }
            
        }, 3000); 
    });

<?php } else { ?>

    //alert("er");
<?php } ?>
    
</script>