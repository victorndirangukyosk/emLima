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
                        <table class="payment-details-table mt-4">
                            <thead>
                            <tr>
                                <td colspan="2" class="text-left">
                                    ORDER ID
                                </td>
                                <td colspan="2" class="text-right">
                                 <?= '#'.$order['order_id']?>   
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left">
                                    ORDER STATUS
                                </td>
                                <td colspan="2" class="text-right">
                                 <?= '#'.$order['order_id']?>   
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left">
                                    DATE OF DELIVERY
                                </td>
                                <td colspan="2" class="text-right">
                                 <?= $order['delivery_date']?>   
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left">
                                    DELIVERY TIMESLOT
                                </td>
                                <td colspan="2" class="text-right">
                                <?= $order['delivery_timeslot']?>      
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left">
                                    COMPANY NAME
                                </td>
                                <td colspan="2" class="text-right">
                                 <?= $order['customer_company_name']?>        
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left">
                                    DELIVERY LOCATION
                                </td>
                                <td colspan="2" class="text-right">
                                 <?= $order['shipping_address']?>        
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                        <table class="datatable">
                            <thead class="datatable-header">
                            <tr>
                                <td>SKU</td>
                                <td>Product</td>
                                <td>Product Notes</td>
                                <td class="text-center">Quantity</td>
                            </tr>
                            </thead>
                            <tbody class="datatable-content">
                            <?php foreach($order['products'] as $product) { ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['product_note'] ?></td>
                                <td class="text-center"><?= $product['quantity'] ?> <?= $product['unit'] ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <h5><strong>Order Notes </strong></h5>
                            <p><?= $order['comment']?></p>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
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
