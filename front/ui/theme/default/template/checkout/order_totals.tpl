
<?php foreach ($totals as $total) { ?>

<?php if($total['title'] == 'Sub-Total') { $p= 0; ?>
<div class="checkout-total">
    <div class="checkout-invoice">
        <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
        <div class="checout-invoice-price"><?php echo $total['text']; ?></div>
    </div>

    <?php } elseif(strpos($total['title'], 'Entrega') !== false || strpos($total['title'], 'Delivery') !== false) { $p= 1; ?>
    <div class="checkout-charges">
        <div class="checout-charges-title"><?php echo $total['title']; ?></div>
        <div class="checout-charges-price charges-free"><?php echo $total['text']; ?></div>
    </div>


<?php } elseif($total['title'] == 'Total') { ?>
     </div>
    <div class="checkout-payable">
        <div class="checkout-payable-title"><?php echo $total['title']; ?><small>(<?= $text_inc_tax ?>)</small></div>
        <div class="checkout-payable-price"><?php echo $total['text']; ?></div>
    </div>

   <?php } elseif(strpos($total['title'], 'Coupon') !== false) { ?>


    <div class="checkout-invoice">
        <div class="checkout-payable-title"><?php echo $total['title']; ?></div>
        <?php if(strpos($total['text'], '0.00') !== false && !$coupon_cashback) { ?>
            <div class="checkout-payable-price" style="color: red" ><?= $cashbackAmount; ?> <sup>*</sup></div>
        <?php } elseif(strpos($total['text'], '0.00') !== false && $coupon_cashback) { ?>
            <div class="checkout-payable-price" style="color: green"><?= $cashbackAmount; ?> <sup>*</sup></div>
        <?php } else { ?>
            <div class="checkout-payable-price"><?php echo $total['text']; ?></div>
        <?php } ?>
        
    </div>

<?php } else { ?>
        
        <div class="checkout-invoice">
            <div class="checout-invoice-title"><?php echo $total['title']; ?></div>
            <div class="checout-invoice-price"><?php echo $total['text']; ?></div>
        </div>
    
<?php } } ?>
<div class="checkout-payable">
        <div class="checkout-payable-price"><small>*<?= $cashback_condition; ?></small></div>
    </div>