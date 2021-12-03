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
                                <td class="text-right"><?= $product['total'] ?></td>
                            </tr>
                            <?php } ?>
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
