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
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-4 text-right">
                                <h5 class="bold">TAX INVOICE #<?= $order['invoice_no'] ?></h5>
                                <?php if($order['po_number']) { ?>
                                    <h5 class="bold">P.O. NUMBER <?= $order['po_number'] ?></h5>
                                <?php } ?>
                                <h5><?= $order['delivery_date'] ?></h5>
                                
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <h5 class="bold text-uppercase mb-3">TO <?= $order['customer_company_name'] ?></h5>
                                <ul class="list-block">
                                    <li><?= $order['shipping_name'] ?></li>
                                    <li><?= $order['telephone'] ?></li>
                                    <li class="mb-2"><?= $order['email'] ?></li>
                                    <li>
                                        <p class="bold"><?= $order['shipping_name_original'] ?></br> <?= $order['shipping_address'] ?></p>
                                    </li>
                                </ul>

                            </div>

                            <div class="col-md-4 offset-md-4 text-right">
                                <h6 class="bold mb-3">ORDER INFO</h6>
                                <ul class="list-block">
                                    <li>Order # <?= $order['order_id'] ?></li>
                                    <li>Placed On <?= $order['date_added'] ?></li>
                                    <li>Delivered On <?= $order['delivery_date'] ?></li>
                                    <li><?= $order['shipping_method'] ?></li>
                                </ul>
                                <?php if($order['driver_name'] != NULL) { ?>
                                <br>
                                <h6 class="bold mb-3">DELIVERY EXECUTIVE DETAILS</h6>
                                <ul class="list-block">
                                    <li>Name <?= $order['driver_name'] ?></li>
                                    <li>Phone <?= $order['driver_phone'] ?></li>
                                </ul>
                                <br>
                                <?php } ?>
                            </div>
                        </div>

                        <table class="datatable">
                            <thead class="datatable-header">
                            <tr>
                                <td>SKU</td>
                                <td>Product</td>
                                <td class="text-center">Quantity</td>
                                <td class="text-right">Unit Price</td>
                                <td class="text-right">Total</td>
                            </tr>
                            </thead>
                            <tbody class="datatable-content">
                            <?php foreach($order['products'] as $product) { ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td class="text-center"><?= $product['quantity'] ?> <?= $product['unit'] ?></td>
                                <td class="text-right"><?= $product['price'] ?></td>
                                <td class="text-right"><?= $product['total'] ?></td>
                            </tr>
                            <?php } ?>

                            <?php foreach($order['totals'] as $total) { ?>
                            <tr>
                                <td colspan="4" class="bold text-right"><?= $total['title'] ?></td>
                                <td class="bold text-right"><?= $total['text'] ?></td>
                            </tr>
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
                        
                        <table class="payment-details-table mt-4">
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
                                    <br>
                                    <h6 class="bold">LIPA NA MPESA</h6>
                                    <ul class="list-block">
                                        <li>Go to the M-PESA Menu</li>
                                        <li>Select Lipa Na M-PESA</li>
                                        <li>Select Pay Bill</li>
                                        <li>Enter <strong>4029127</strong></li>
                                        <li>Enter <strong>KB<?= $order['order_id'] ?></strong> as the account number</li>
                                        <li>Enter total amount</li>
                                        <li>Enter M-PESA Pin</li>
                                    </ul>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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