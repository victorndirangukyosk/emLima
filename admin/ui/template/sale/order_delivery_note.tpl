<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KwikBasket Invoice #<?= $orders[0]['order_id'] ?></title>
    <link rel="stylesheet" href="ui/stylesheet/bootstrap.min.css">
    <link rel="stylesheet" href="ui/javascript/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="ui/stylesheet/print.css">
</head>

<body>        
<?php foreach($orders as $order) { ?>
    
<div class="page">
    <table class="document-container">
        <tbody>
        <tr>
            <td>
                <div class="content">
                    <div class="container">
                        <div class="row mb-4">
                            <div class="col-md-4 company-details">
                                <img width="210" src="ui/images/logo.png" alt="KwikBasket Logo" class="mb-2">
                                <div class="text-left address-block">
                                    <ul class="list-block">
                                        <li>12 Githuri Rd, Parklands, Nairobi</li>
                                        <li>+254780703586</li>
                                        <li>operations@kwikbasket.com</li>
                                        <li>www.kwikbasket.com</li>
                                        <li>KRA PIN Number P051904531E</li>
                                    </ul>
                                    <br><br>
                                       <h6 class="bold text-uppercase mb-3">TO <?= $order['customer_company_name'] ?></h6>
                                <ul class="list-block">
                                    <li><?= $order['shipping_name'] ?></li>
                                    <li><?= $order['telephone'] ?></li>
                                    <li class="mb-2"><?= $order['email'] ?></li>
                                    <li>
                                        <p class="bold"><?= $order['shipping_name_original'] ?></br> <?= $order['shipping_address'] ?></p>
                                    </li>
                                </ul>

                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                               <!-- <?php if(($order['vendor_terms_cod'] == 1 || $order['payment_terms'] == 'Payment On Delivery') && $order['payment_method'] != 'Pezesha') { ?>
                               <img width="210" src="ui/images/pod.png" alt="POD" class="mb-2">

                                <?php } else if($order['paid'] != 'Y' && $order['payment_method'] == 'Pezesha') { ?>
                               <img width="210" src="ui/images/pezesha.jpg" alt="Pezesha" class="mb-2">
                                <?php } else if($order['paid'] == 'Y' && $order['order_transcation_id']!= '' && $order['order_transcation_id'] !=NULL) { ?>
                               <img width="210" src="ui/images/pre-paid.jpg" alt="Pre-Paid" class="mb-2">

                                <?php } else { ?>
                                <ul class="list-block" style="margin-bottom:195px;">
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>
                                <?php } ?>-->
                                  <br>
                                 <!--<h6 class="bold mb-3">ORDER INFO</h6>
                                <ul class="list-block">
                                    <li>Order # <?= $order['order_id'] ?></li>
                                    <li>Placed On <?= $order['date_added'] ?></li>
                                    <li>Delivered On <?= $order['delivery_date'] ?></li>
                                    <li><?= $order['shipping_method'] ?></li>
                                </ul>-->
                            </div>
                            <div class="col-md-5 text-right">
                              <h4 class="bold mb-3">Delivery Note # <?= $order['order_id'] ?><?= $order['invoice_no'] ?></h4> 
                                <?php if($order['po_number']) { ?>
                                    <h6 class="bold">P.O. NUMBER <?= $order['po_number'] ?></h6>
                                <?php } ?>
                                <h6><?= $order['delivery_date'] ?></h6>

                                
                                <br>
                                  <!-- <h6 class="bold mb-3">PAYMENT DETAILS</h6>                                
                                <ul class="list-block" style="margin-bottom:60px;">
                                   
                                 <?php if($order['order_transcation_id']!=NULL && $order['order_transcation_id'] !='' && $order['paid'] =='Y') { ?>
                                    <li>Payment Method : <?= $order['payment_method'] ?></li>
                                    
                                    <li>Transaction ID : <?= $order['order_transcation_id'] ?></li>
                                     <?php } else { ?>
                                      <li>Payment Method : <label style="width:40px"></label> </li>
                                      
                                      <li></li>
                                    
                                    <li>Transaction ID :  <label style="width:40px"></label> </li>
                                     <?php }?>
                                
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                    <li></li>
                                </ul>-->
                                <!--<?php if($order['driver_name'] != NULL) { ?>
                                <h6 class="bold mb-3">DRIVER DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name : <?= $order['driver_name'] ?></li>
                                    <li>Phone : <?= $order['driver_phone'] ?></li>
                                </ul>
                                <br>
                                <?php } ?>-->

                                <?php if($order['delivery_executive_name'] != NULL) { ?>
 
                                <h6 class="bold mb-3">DELIVERY EXECUTIVE DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name : <?= $order['delivery_executive_name'] ?></li>
                                    <li>Phone : <?= $order['delivery_executive_phone'] ?></li>
                                    
                                <!--<?php if($order['delivery_charge'] != NULL && $order['delivery_charge'] >0) { ?>
                                    <li>Delivery Charge : <?= $order['delivery_charge'] ?></li>
                                 <?php } ?>-->
                                </ul>
                                <br>
                                <?php } ?>
                                
                                 <!--<?php if($order['customer_experience_first_last_name'] != NULL) { ?>
                                 
                               <h6 class="bold mb-3">CUSTOMER ACCOUNT MANAGER DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name : <?= $order['customer_experience_first_last_name'] ?></li>
                                    <li>Phone : <?= $order['customer_experince_phone'] ?></li>-->
                                <!--<?php if($order['delivery_charge'] != NULL && $order['delivery_charge'] >0) { ?>
                                    <li>Delivery Charge : <?= $order['delivery_charge'] ?></li>
                                 <?php } ?>-->
                                 <!--</ul>
                                <br>
                                <?php } ?>-->
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                
                        <h6 class="bold mb-3">ORDER INFO</h6>
                                
                                  <ul class="list-block">
                                    <li>Order # <?= $order['order_id'] ?></li>
                                    <li>Placed On <?= $order['date_added'] ?></li>
                                    <li>Delivered On <?= $order['delivery_date'] ?></li>
                                    <li><?= $order['shipping_method'] ?></li>


                                    
                                </ul>

                                
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                             

                            </div>

                            <div class="col-md-4 offset-md-4 text-right">
                               
                            </div>
                        </div>

                        <table class="datatable">
                            <thead class="datatable-header">
                            <tr>
                                <td>SKU</td>
                                <td>Product</td>
                                <td>Product Notes</td>
                                <td class="text-center">Quantity</td>
                                <td class="text-right">Unit Price</td>
                                <?php if($order['show_discount'] == TRUE) { ?>
                                <td class="text-right">Discount</td>
                                <?php } ?>
                                <td class="text-right">Total</td>
                            </tr>
                            </thead>
                            <tbody class="datatable-content">
                            <?php foreach($order['products'] as $product) { ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['product_note'] ?></td>
                                <td class="text-center"><?= $product['quantity'] ?> <?= $product['unit'] ?></td>
                                <td class="text-right"><?= $product['price'] ?></td>
                                <?php if($order['show_discount'] == TRUE) { ?>
                                <td class="text-right"><?= $product['discount_amount'] ?></td>
                                <?php } ?>
                                <td class="text-right"><?= $product['total'] ?></td>
                            </tr>
                            <?php } ?>
                            
                            <?php if($order['show_discount'] == TRUE) { ?>
                            <?php foreach($order['totals'] as $total) { ?>
                            <tr>
                             <?php if($total['title'] == 'VAT on Standard Delivery') { ?>
                                <td colspan="6" class="text-right" >
                                <span class="bold text-right"><?= $total['title'] ?></span>                                
                                <span style="font-weight:2px"> (VAT16)</span></td>
                                <?php } else { ?>
                                <td colspan="6" class="bold text-right" ><?= $total['title'] ?></td> 
                                <?php }   ?>
                                <td class="bold text-right"><?= $total['text'] ?></td>
                            </tr>
                            <?php } ?>
                            <?php }  else { ?>                                                            
                            <?php foreach($order['totals'] as $total) { ?>
                            <tr>
                             <?php if($total['title'] == 'VAT on Standard Delivery') { ?>
                                <td colspan="5" class="text-right" >
                                <span class="bold text-right"><?= $total['title'] ?></span>                                
                                <span style="font-weight:2px"> (VAT16)</span></td>
                                <?php } else { ?>
                                <td colspan="5" class="bold text-right" ><?= $total['title'] ?></td> 
                                <?php }   ?>
                                <td class="bold text-right"><?= $total['text'] ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                        
                        <div class="mt-4">
                            <?php foreach($order['totals'] as $total) { ?>
                                <?php if($total['title'] == 'Total') { ?>
                                    <h5><strong>Total In Words </strong> <?= $total['amount_in_words']?></h5>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="mt-4">
                            <h5><strong>Order Notes </strong></h5>
                            <p><?= $order['comment']?></p>
                        </div>
                        
                        <!--<table class="payment-details-table mt-4">
                            <thead>
                            <tr>
                                <td colspan="2" class="text-left">
                                    PAYMENT DETAILS
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <h6 class="bold">BANK TRANSFER</h6>
                                    <ul class="list-block">
                                        <li>Beneficiary Name: KWIKBASKET SOLUTIONS LIMITED</li>
                                        <li>Account Currency: KES</li>
                                        <li>Account Number: 0100006985957</li>
                                        <li>Bank Name: STANBIC BANK KENYA LTD</li>
                                        <li>Sort Code: 31007</li>
                                        <li>Branch: Chiromo Road, Nairobi</li>
                                        <li>SWIFT Code: SBICKENX</li>
                                    </ul>
                                </td>
                                <td class="text-right">
                                    <h6 class="bold">PAYMENT ON DELIVERY</h6>
                                    <ul class="list-block">
                                        <li>Step 1: Goto my account</li>
                                        <li>Step 2: Click on my transactions</li>
                                        <li>Step 3: Click on Mpesa online</li>
                                        <li>Step 4: Verify your Mpesa number as shown on platform</li>
                                        <li>Step 5: Click to pay & confirm</li>
                                        <li>Step 6: Click on confirm payment</li>
                                    </ul>
                                </td>
                            </tr>
                            </tbody>
                        </table>-->
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
        <tfoot class="page-footer">
        <tr>
            <td>
                <div class="footer-space">&nbsp;</div>
            </td>
        </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-left">
                    <ul class="list-block">
                        <li>KWIKBASKET SOLUTIONS LIMITED</li>
                        <li>3rd Floor, Heritan House, Woodlands Road</li>
                        <li>Nairobi, Kenya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<div class="document-actions">
    <button class="btn btn-primary mb-2" onclick="printDocument()"><i class="fa fa-print"></i> Print Document</button>
</div>

<script>
    function printDocument()
    {
        window.print();
    }
</script>
</body>
</html>