<!-- <table class="cart-info-table">
    <tbody>
        <?php foreach($totals as $total){ ?>
        <tr>
            <td class="big">
                <?= $total['title'] ?>:
            </td>
            <td><?= $total['text'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table> -->
<div class="checkout-total">

       <?php foreach ($totals as $total) { ?>

            <?php if($total['title'] == 'Total') { ?>
                <?php if(count($totals) <= 2) { ?> 
                <?php } ?>
                
                </div>
                 <div class="checkout-payable">
                    <div class="checkout-payable-title"><?php echo $total['title']; ?></div>
                    <div class="checkout-payable-price"><?php echo $total['text']; ?></div>
                </div>

                
            <?php } elseif(strpos($total['title'], 'Delivery') !== false || strpos($total['title'], 'Entrega') !== false ) { ?>
                    <div class="checkout-invoice">
                        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                        <div class="checout-invoice-price charges-free"><?php echo $total['text']; ?></div>
                    </div>

            <?php } elseif(strpos($total['title'], 'Coupon') !== false) { ?>


                    <div class="checkout-invoice">
                       <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                         <!--<?php if(strpos($total['text'], '0.00') !== false) { ?>
                            <div class="checout-invoice-price" style="color: red"><?= $cashbackAmount; ?><sup>*</sup></div>
                        <?php } elseif(strpos($total['text'], '1.00') !== false) { ?>
                            <div class="checout-invoice-price"><?= $text_coupon_credited; ?></div>
                        <?php } else { ?>
                            <div class="checout-invoice-price"><?php echo $total['text']; ?></div>
                        <?php } ?>-->
                            <div class="checout-invoice-price"><?php echo $total['text']; ?></div>
                        
                    </div>

            
            <?php /* } else { */ ?>
            <?php  } elseif($total['title'] != 'Transaction-Fee') {  ?>
                <div class="checkout-invoice">
                    <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
                    <div class="checout-invoice-price"><?php echo $total['text']; ?></div>
                </div>
        <?php } }?>

        <div class="checkout-invoice">
            <div class="checkout-payable-price"><small>*<?= $cashback_condition; ?></small></div>
        </div>
        <?php if($min_order_amount_reached == FALSE && $min_order_amount_away != NULL) ?>
        <div class="checkout-invoice">
            <div class="checkout-payable-price"><small style="text-transform: none !important;"><?= $min_order_amount_away; ?></small></div>
        </div>
        <?php ?>
</div>
