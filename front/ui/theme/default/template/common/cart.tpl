<div class="cart-info">
    <div class="arrow-down"></div>
    <div class="cart-icon"></div>
    <div class="item-count">
        <span class="item-num"><?= $this->cart->countProducts() ?></span>
        <span><?= $text_item ?></span>
    </div>
    <div class="pull-right">
        <span class="title">
            <?= $text_total ?>
        </span>
        <span class="cart-total"><?= $this->currency->format($this->cart->getTotal()) ?></span>
    </div>
</div>
<div class="empty-cart-dropdown">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 homecartcontainer">
                
                <?php if (!$products) { ?>
                
                    <div style="display: block;" class="emptycartdtls-container">
                        <div class="emptycartdtls">
                            <div class="empty-msgs">
                                <h5><?php echo $text_empty; ?></h5>                            
                            </div>
                        </div>
                    </div>                
                    <div style="display: none;" id="cart-table">
                        
                <?php }else{ ?>   
                
                   <div id="cart-table">
                       
                   <table id="normal-items" class="checkouttable homecheckouttable homecheckouttable-popup">
                       <tbody>
                           
                           <?php foreach ($products as $product) { ?>
                           <tr>
                               <td class="itemimg">
                                    <?php if ($product['thumb']) { ?>
                                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
                                    <?php } ?>
                               </td>
                               <td class="itemsdesc">
                                   <div class="item-name">
                                        <?php echo $product['name']; ?>
                                                                               
                                        <?php if ($product['option']) { ?>
                                        <?php foreach ($product['option'] as $option) { ?>
                                        <span>- <?php echo $option['name']; ?> <?php echo $option['value']; ?></span><br />
                                        <?php } ?>
                                        <?php } ?>
                                        <?php if ($product['recurring']) { ?>                                       
                                        - <span><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></span>
                                        <?php } ?>
                                        
                                   </div>
                                   <div class="unitprice">
                                       <span class="approx-title tooltips"><?= $text_price ?></span>
                                       <span class="price"><?php echo $product['total']; ?></span>
                                   </div>
                                   <div class="quantity-controller">
                                       <div class="minus" data-key="<?php echo $product['key']; ?>"></div>
                                       <div class="num"><?php echo $product['quantity']; ?></div>
                                       <div class="plus" data-key="<?php echo $product['key']; ?>"></div>
                                   </div>
                               </td>
                               <td class="dlt-itm-col">
                                   <a class="deleteitem" onclick="cart.remove('<?php echo $product['key']; ?>');" ></a>
                               </td>
                           </tr>
                           <?php } ?>
                           
                           <?php foreach ($vouchers as $voucher) { ?>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-left"><?php echo $voucher['description']; ?></td>
                                <td class="text-right">x&nbsp;1</td>
                                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                                <td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
                            </tr>
                            <?php } ?>
                            
                       </tbody>
                   </table>    
                
                <?php } ?>
                
                </div>
            </div>
                
            <div class="centerbtnbox">                
                <?php if (!$products) { ?>
                    <button class="btn-disabled btn-full-large" type="submit"><?= $button_view_place ?></button>
                <?php }else{ ?>
                <a href="<?= $this->url->link('checkout/cart') ?>" class="btn-orange btn-full-large"><?= $button_view_place ?></a>
                <?php } ?>
            </div>
                
        </div>
    </div>
</div>

