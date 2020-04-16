<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="ui/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="ui/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="ui/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="ui/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <?php foreach ($orders as $order) { ?>
  <div style="page-break-after: always;">
    <h1><?php echo $text_picklist; ?> #<?php echo $order['order_id']; ?></h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td style="width: 50%;"><?php echo $text_from; ?></td>
          <td style="width: 50%;"><?php echo $text_order_detail; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><address>
            <strong><?php echo $order['store_name']; ?></strong><br />
            <?php echo $order['store_address']; ?>
            </address>
            <b><?php echo $text_telephone; ?></b> <?php echo $order['store_telephone']; ?><br />
            <?php if ($order['store_fax']) { ?>
            <b><?php echo $text_fax; ?></b> <?php echo $order['store_fax']; ?><br />
            <?php } ?>
            <b><?php echo $text_email; ?></b> <?php echo $order['store_email']; ?><br />
            <b><?php echo $text_website; ?></b> <a href="<?php echo $order['store_url']; ?>"><?php echo $order['store_url']; ?></a></td>
          <td style="width: 50%;"><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
            <?php if ($order['invoice_no']) { ?>
            <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_no']; ?><br />
            <?php } ?>
            <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?><br />
            <?php if ($order['shipping_method']) { ?>
            <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?><br />
            <?php } ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td style="width: 50%;"><b><?php echo $text_to; ?></b></td>
          <td style="width: 50%;"><b><?php echo $text_contact; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
              <b><?= $text_name ?></b> <?php echo $order['shipping_name']; ?>,<br />
              <b><?= $text_contact_no ?></b> <?php echo $order['shipping_contact_no']; ?>,<br />
              
              <?php echo $order['shipping_address'] ?><br />
              <?php echo $order['shipping_city']; ?>            
            
          </td>
          <td><?php echo $order['email']; ?><br/>
            <?php echo $order['telephone']; ?></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
         
          
          <td><b><?php echo $column_product; ?></b></td>
         
          
          <td><b><?php echo $column_unit; ?></b></td>
          <td><b><?php echo $column_model; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <?php $t = 0; foreach ($order['product'] as $product) {  $t += $product['quantity']; ?>
        <tr>
         
          <td><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td><?php echo $product['unit']; ?></td>
          <td><?php echo $product['model']; ?></td>

          <td class="text-right"><?php echo $product['quantity']; ?></td>
        </tr>
        <?php } ?>

        <tr>
         
          
          <td></td>
         
          
          <td></td>
          <td>Total</td>
          <td class="text-right"><b><?php echo $t; ?></b></td>
        </tr>

      </tbody>
    </table>
    <?php if ($order['comment']) { ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $column_comment; ?></b></td>
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
  <?php } ?>
</div>
</body>
</html>