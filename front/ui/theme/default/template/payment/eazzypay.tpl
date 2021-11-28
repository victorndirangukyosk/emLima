<!-- EazzyCheckout.configure({
     token: '<?= $token ?>',

     amount: '<?= $amount ?>',
     currency: '<?= $currency ?>',
     orderRef: '<?= $order_id ?>',
     merchantCode:'<?= $merchantCode ?>',
     outletCode:'0000000000',
     payButtonSelector: "#checkout-btn",
     merchant:'<?= $merchant ?>',
     expiry:'<?= $expiry ?>',             
     //popupTheme:JSON.stringify({"logo":$('#logoColor').val() || "#f6971a","buttons":$('#buttonColor').val() || "#F49920","tabs":$('#tabsColor').val() || "#F6981B"}),
     //optional fields below
     /*description:$('#description').val(),
     custName:$('#custName').val(),
     custId:"optional",
     ez1_callbackurl:$('#ez1_callbackurl').val(),
     ez2_callbackurl:$('#ez2_callbackurl').val(),
     popupTitle:$('#popupTitle').val(),
     popupLogo:$('#popupLogo').val(),
     popupBtns:["Confirm Payment","OK"],
     popupTheme:JSON.stringify({"logo":$('#logoColor').val() ||
    "#f6971a","buttons":$('#buttonColor').val() || "#F49920","tabs":$('#tabsColor').val() ||
    "#F6981B"}),
     popupWebsite:$('#popupWebsite').val(),
     */
}); -->


<button type="button" id="button-confirm" data-toggle="collapse" data-loading-text="<?= $text_loading ?>" class="btn btn-default"><?= $button_confirm?></button>


<script type="text/javascript">
    //$("#button-confirm").click(function(){
    $(document).ready(function(){

        console.log('<?= $expiry ?>');
        console.log('<?= $token ?>');
        console.log('<?= $order_id ?>');
        
        EazzyCheckout.configure({
            token: '<?= $token ?>',

             amount: '<?= $amount ?>',
             currency: '<?= $currency ?>',
             orderRef: '<?= $order_id ?>',
             merchantCode:'<?= $merchantCode ?>',
             outletCode:'0000000000',
             payButtonSelector: "#button-confirm",
             merchant:'<?= $merchant ?>',
             expiry:'<?= $expiry ?>', 
             ez1_callbackurl:'<?= $continue ?>', 
            //popupTheme:JSON.stringify({"logo":$('#logoColor').val() || "#f6971a","buttons":$('#buttonColor').val() || "#F49920","tabs":$('#tabsColor').val() || "#F6981B"}),          
        });
    });
</script> 

<!-- <script src="https://api-test.equitybankgroup.com/js/eazzycheckout.js"></script>

<form id="eazzycheckout-payment-form" action="https://api-test.equitybankgroup.com/js/eazzycheckout.js" method="POST">

<input type='hidden' name="orderRef" id="orderRef" value="<?= $order_id ?>"/>
<input type='hidden' name="merchantCode" id="merchantCode" value="<?= $merchantCode ?>"/>
<input type='hidden' name="outletCode" id="outletCode" value="0000000000"/>
<input type='hidden' name="token" id="token" value="<?= $token ?>"/>
<input type='hidden' name="amount" id="amount" value="<?= $amount ?>"/>
<input type='hidden' name="currency" id="currency" value="KSH"/>
<input type='hidden' name="merchant" id="merchant" value="<?= $merchant ?>"/>

<input type='hidden' name="ez1_callbackurl" id="ez1_callbackurl" value="https://www.npmjs.com/package/js-logging"/>
<input type='hidden' name="ez2_callbackurl" id="ez2_callbackurl" value="https://www.npmjs.com/package/js-logging"/>
<input type='hidden' name="popupTitle" id="popupTitle" value="popupTitle"/>
<input type='hidden' name="popupSubTitle" id="popupSubTitle" value="custId"/>
<input type='hidden' name="popupLogo" id="popupLogo" value="http://xyz.svg"/>
<input type='hidden' name="popupBtns" id="popupBtns" value="popupBtns"/>
<input type='hidden' name="popupTheme" id="popupTheme" value="popupBtns"/>
<input type='hidden' name="popupWebsite" id="popupWebsite" value="popupBtns"/>
<input type='hidden' name="expiry" id="expiry" value="<?= $expiry ?>"/>
<input type="submit" id="submit-cg" value="Submit"/> 
</form> -->

