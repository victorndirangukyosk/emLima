<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>

    <body>
        <div id="content">
            <div class="page-header">
                <div class="container-fluid" style="align:center">
                    <h1 >Customer Orders Statement</h1>

                </div>
            </div>
            <div class="container-fluid">
                <div class="panel panel-default">
                    <div class="panel-heading">


                    </div>
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>

                                        <td class="text-left">Customer Name</td>
                                        <td class="text-left">Company Name</td>
                                        <td class="text-right">Order Id</td> 
                                        <td class="text-right">Order Date</td> 
                                        <td class="text-right">Delivery Date</td> 
                                        <td class="text-right">P.O. Number</td>
                                        <td class="text-right">Order value</td>
                                        <td class="text-right">Amount Paid</td>
                                        <td class="text-right">Pending Amount</td>
                                        <td class="text-right">Payment Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 


                                    if ($customers) { ?>
                                    <?php foreach ($customers as $customer) { ?>
                                    <tr>
                                        <td class="text-left"><?php echo $customer['customer']; ?></td>
                                        <td class="text-left"><?php echo $customer['company']; ?></td>
                                        <td class="text-right"><?php echo $customer['order_id']; ?></td> 
                                        <td class="text-right"><?php echo $customer['date_added']; ?></td> 
                                        <td class="text-right"><?php echo $customer['delivery_date']; ?></td> 
                                        <td class="text-right"><?php echo $customer['po_number']; ?></td>
                                        <td class="text-right"><?php echo $customer['subtotal']; ?></td>
                                        <td class="text-right"><?php echo $customer['amountpaid']; ?></td>
                                        <td class="text-right"><?php echo $customer['pendingamount']; ?></td>
                                        <td class="text-right"><?php echo $customer['paid']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <tr>
                                        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </body>

</html>      


