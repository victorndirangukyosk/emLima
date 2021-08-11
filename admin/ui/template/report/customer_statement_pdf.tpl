<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
   
   <style>

   thead { display: table-header-group; }
tfoot { display: table-row-group; }
tr { 
  page-break-inside: avoid !important;
}

thead {
display: table-row-group;
}
tfoot {
display: table-row-group;
}
tr { 
page-break-inside: avoid;
}

.table-responsive { overflow-x: visible !important; }

   </style>
   
    </head>

    <body>
        <div id="content">
            <div class="page-header">
                <div class="container-fluid" style="align:center">
                    <h1 >Customer Orders Statement</h1>
                     <?php  if ($customers) { ?>
                     <h3 >Company : <?php echo $customers[0]['company']; ?></h3>
                    <h3 >Customer: <?php echo $customers[0]['customer']; ?> </h3>
                     <?php } ?>


                </div>
            </div>
            <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="table-layout:fixed;">
                                <thead>
                                    <tr>
                                        <td style="width:7%;" class="text-right">Order Id</td> 
                                        <td style="width:15%;" class="text-left">Customer</td>
                                        <td class="text-right">Order <br>Date</td> 
                                        <td class="text-right">Delivery <br>Date</td> 
                                        <td class="text-right">Order <br>value</td>
                                        <td class="text-right">Amount <br>Paid</td>
                                        <td class="text-right">Pending <br>Amount</td>
                                        <td class="text-right">Payment <br>Status</td>
                                    </tr>
                                </thead>
                                <tbody style="padding-bottom: 50px;padding-top:50px">
                                    <?php 


                                    if ($customers) { ?>
                                    <?php foreach ($customers as $customer) { ?>
                                    <tr>
                                        <td style="width:7%;" class="text-right"><?php echo $customer['order_id']; ?></td> 
                                        <td style="width:15%;" class="text-left"><?php echo $customer['customer']; ?><br>(<?php echo $customer['company']; ?>)</td>
                                        <td class="text-right"><?php echo $customer['date_added']; ?></td> 
                                        <td class="text-right"><?php echo $customer['delivery_date']; ?></td> 
                                        <td class="text-right"><?php echo $customer['subtotal']; ?></td>
                                        <td class="text-right"><?php echo $customer['amountpaid']; ?></td>
                                        <td class="text-right"><?php echo $customer['pendingamount']; ?></td>
                                        <td class="text-right"><?php echo $customer['paid']; ?></td>
                                    </tr>
                                    <?php } ?>

                                      <?php  if ($customers) { ?>
                      
                      <tr>
                                 <td  colspan="3" class="text-right">
                                     <b>Grand Total</b>
                                    </td>
                                    
                                    <td colspan="2" class="text-right"><?php echo $customers[0]['Amount_ordervalue_grand']; ?></td>
                                    <td  class="text-right"><?php echo $customers[0]['Amount_paid_grand']; ?></td>
                                    <td   class="text-right"><?php echo $customers[0]['Amount_pending_grand']; ?></td>
                                    
                                    
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
    </body>

</html>      


