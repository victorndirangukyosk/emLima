<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?= $heading_title ?>Shopper Order </title>
        <base href="<?php echo $base; ?>" />
        <link href="front/ui/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="all" />
        <link type="text/css" href="front/ui/theme/default/stylesheet/shopper_invoice.css" rel="stylesheet" media="all" />
    </head>
    <body>
        <div class="container">
            <div style="page-break-after: always;">
                <h1><?= $heading_text ?>Invoice #<?php echo $order_id; ?></h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="2"><?= $text_order_details ?>Order detail</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 50%;"><address>
                                <strong><?php echo $order['store_name']; ?></strong><br />
                                <?php echo $order['store_address']; ?>
                                </address>
                                <b><?= $entry_telephone ?>Telephone:</b> <?php echo $order['store_telephone']; ?><br />
                                <?php if ($order['store_fax']) { ?>
                                <b><?= $entry_fax ?>Fax:</b> <?php echo $order['store_fax']; ?><br />
                                <?php } ?>
                                <b><?= $entry_email ?>Email:</b> <?php echo $order['store_email']; ?><br />
                            </td>
                            <td style="width: 50%;"><b>Date added:</b> <?php echo $order['date_added']; ?><br />
                                <?php if ($order['invoice_no']) { ?>
                                <b><?= $entry_invoice_no ?>Invoice no:</b> <?php echo $order['invoice_no']; ?><br />
                                <?php } ?>
                                <b><?= $entry_vendor_order_id ?>Vendor Order ID:</b> <?php echo $order['order_id']; ?><br />
                                <b><?= $entry_payment_method ?>Payment method:</b> <?php echo $order['payment_method']; ?><br />
                                <?php if ($order['shipping_method']) { ?>
                                <b>Shipping method</b> <?php echo $order['shipping_method']; ?><br />
                                <?php } ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td style="width: 50%;"><b><?= $column_to ?>To</b></td>
                            <td style="width: 50%;"><b><?= $column_shipping_address ?>Shipping Address</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <address>
                                    <b><?= $text_name ?>Name:</b> <?php echo $order['shipping_name']; ?>, <br />
                                    <b><?= $text_contact_no ?>Contact No:</b> <?php echo $order['shipping_contact_no']; ?>
                                </address>
                            </td>
                            <td><address>
                                    <?php echo $order['shipping_address']; ?>
                                </address></td>
                        </tr>
                    </tbody>
                </table>

                <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
                    <thead>
                      <tr>
                        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Product</td>
                        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Model</td>
                        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Quantity</td>
                        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Price</td>
                        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Total</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($order['products'] as $product) { ?>
                      <tr>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['name']; ?>
                          <?php foreach ($product['option'] as $option) { ?>
                          <br />
                          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                          <?php } ?></td>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['model']; ?></td>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['quantity']; ?></td>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['price']; ?></td>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['total']; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <?php foreach ($order['total'] as $total) { ?>
                      <tr>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
                        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $total['text']; ?></td>
                      </tr>
                      <?php } ?>
                    </tfoot>
                  </table>

                <?php if ($order['comment']) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td><b><?= $text_comment ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $order['comment']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
    </body>
</html>