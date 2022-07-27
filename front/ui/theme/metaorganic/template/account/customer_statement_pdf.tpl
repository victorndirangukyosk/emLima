
<!DOCTYPE html>
<html lang="en">
 
<head>
    <link rel="stylesheet" href="https://kwikbasket-assets.s3.ap-south-1.amazonaws.com/fonts/sofiapro/index.css">
    <!-- CSS Reset -->
   
 
   
    </head>

    <body>
        <div id="content">
            <div class="page-header">
                <div class="container-fluid" style="align:center">
                    <h1 >Customer Orders Statement</h1>
                     <?php  if ($customers) { ?>
                     <h3 >Company : <?php echo $company; ?></h3>
                    <h3 >Customer: <?php echo $customer; ?> </h3>
                     <?php } ?>


                </div>
            </div>
            <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="table-layout:fixed;border: 1px solid black;border-collapse:collapse; ">
                                <thead>
                                    <tr>
                                        <td style="width:6%;border: 1px solid black;" class="text-right"><b>Order Id</b></td> 
                                        <td style="width:15%;border: 1px solid black;" class="text-left"><b>Customer</b></td>
                                        <td style="width:10%;border: 1px solid black;" class="text-right"><b>Order Date</b></td> 
                                        <td style="width:10%;border: 1px solid black;" class="text-right"><b>Delivery Date</b></td> 
                                        <td style="width:15%;border: 1px solid black;text-align:right" class="text-right"><b>Order value</b></td>
                                        <td style="width:10%;border: 1px solid black;text-align:right" class="text-right"><b>Payment <br> Status</b></td>

                                        <td style="width:15%;text-align:right;border: 1px solid black;" class="text-right"><b>Amount Paid</b></td>
                                        <td style="width:15%;text-align:right;border: 1px solid black;" class="text-right"><b>Pending Amount</b></td>
                                    </tr>
                                </thead>
                                <tbody style="padding-bottom: 50px;padding-top:50px;border: 1px solid black;">
                                    <?php 


                                    if ($customers) { ?>
                                    <?php foreach ($customers as $customer) { ?>
                                    <tr>
                                        <td class="text-right" style="border: 1px solid black;"><?php echo $customer['order_id']; ?></td> 
                                        <td class="text-left" style="border: 1px solid black;"><?php echo $customer['customer']; ?><br>(<?php echo $customer['company']; ?>)</td>
                                        <td class="text-right" style="border: 1px solid black;"><?php echo $customer['date_added']; ?></td> 
                                        <td class="text-right" style="border: 1px solid black;"><?php echo $customer['delivery_date']; ?></td> 
                                        <td style="text-align:right;border: 1px solid black;"><?php echo $customer['total']; ?></td>
                                        <td style="text-align:right;border: 1px solid black;"><?php echo $customer['paid']; ?></td>

                                        <td style="text-align:right;border: 1px solid black;"><?php echo $customer['amount_partialy_paid']; ?></td>
                                        <td style="text-align:right;border: 1px solid black;"><?php echo $customer['pending_amount']; ?></td>
                                    </tr>
                                    <?php } ?>

                                      <?php  if ($customers) { ?>
                      
                       <tr>
                    <td  colspan="3" class="text-right">
                                     
                                    </td>
                       </tr>
                       </br>

                      <tr>
                                 <td  colspan="4" class="text-right">
                                     <b>Grand Total</b>
                                    </td>
                                    
                                    <td  style="text-align:right;"><?php echo $customers[0]['Amount_ordervalue_grand']; ?></td>
                                    <td></td>
                                    <td  style="text-align:right"><?php echo $customers[0]['Amount_paid_grand']; ?></td>
                                    <td   style="text-align:right"><?php echo $customers[0]['Amount_pending_grand']; ?></td>
                                    
                                    
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


