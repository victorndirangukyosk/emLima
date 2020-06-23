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
    <h1><?php echo $text_invoice; ?> #<?php echo $order['order_id']; ?>
      <button type="submit" class="btn btn-lg btn-success" onclick="window.print()" id="printPageButton" ><i class="fa fa-print"></i> Print PDF </button>
    </h1>
    <table class="table table-bordered">
      <thead>
      <!--tr>
      <td style="width: 50%;"><?php echo $text_from; ?></td>
        <td style="width: 50%;"><?php echo $text_order_detail; ?></td>
        <!--td colspan="2"><?php echo $text_order_detail; ?></td-->
      </tr-->
      <tr>
        <td>
          <?php if($store_logo){?>
          <div class="logo-image">
          <img height="50" src="<?php echo $store_logo;?>" alt="Shop" title="Shop" />
          </div>
          <?php } ?>
        </td>
        <td>
          <h3 style="margin: 0"><b>TAX INVOICE</b></h3>
        </td>
      <tr>
      </thead>
      <tbody>
      <tr>
        <td style="width: 50%;"><address>
            <strong><?php echo $order['store_name']; ?></strong><br />
            <?php echo $order['store_address']; ?>
          </address>
          <b><?php echo $text_telephone; ?></b> <?php echo $order['store_telephone']; ?><br />
          <?php if ($order['store_fax']) { ?>
          <b><?php echo $text_fax; ?></b> <?php echo $order['store_fax']; ?><br />
          <?php } ?>
          <b><?php echo $text_email; ?></b> <?php echo $order['store_email']; ?><br />
          <b><?php echo $text_website; ?></b> <a href="<?php echo $order['store_url']; ?>"><?php echo $order['store_url']; ?></a><br />
          <b><?php echo $text_tax; ?></b> <?php echo "P051904531E" ?>
        </td>
        <td style="width: 50%;"><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
          <b><?php echo $text_date_delivered; ?></b> <?php echo $order['delivery_date']; ?> <?php echo $order['delivery_timeslot']; ?> <br />
          <?php if ($order['invoice_no']) { ?>
          <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_no']; ?><br />
          <?php } ?>
          <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?><br />
          <b><?php echo $text_payment_method; ?></b> <?php echo $order['payment_method']; ?><br />
          <?php if ($order['shipping_method']) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?><br />
          <?php } ?>

        </td>
      </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
      <tr>
        <td style="width: 50%;"><?php echo $text_to; ?> <span style="text-transform: uppercase"><?php echo $order['shipping_flat_number']; ?></span></td>
        <!--<td style="width: 50%;"><b><?php echo $text_ship_to; ?></b></td>-->
        <td style="width: 50%;"><b><?php echo $text_po_no; ?></b></td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <address>
            <b><?= $text_name ?></b> <?php echo $order['shipping_name']; ?>, <br />
            <b><?= $text_contact_no ?></b> <?php echo $order['shipping_contact_no']; ?>,<br />

            <?php echo $order['shipping_address'] ?><br />
            <?php echo $order['shipping_city']; ?>

            <!--<b><?php echo $text_cpf_number; ?></b> <?php echo $order['cpf_number']; ?>-->
          </address>
        </td>
        <td>
          <!-- <address>
           <?php echo $order['shipping_address'] ?><br />
           <?php echo $order['shipping_city']; ?>
           </address>-->
          <?php echo $order['email']; ?><br/>
          <?php echo $order['telephone']; ?>
        </td>
      </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
      <tr>
        <td><b><?php echo $column_model; ?></b></td>
        <td><b><?php echo $column_product; ?></b></td>
        <td><b><?php echo $column_unit; ?></b></td>
        <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
        <td class="text-right"><b><?php echo $column_price; ?></b></td>
        <td class="text-right"><b><?php echo $column_total; ?></b></td>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($order['product'] as $product) { ?>
      <tr>
        <td><?php echo $product['model']; ?></td>
        <td><?php echo $product['name']; ?>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td><?php echo $product['unit']; ?></td>
        <td class="text-right"><?php echo $product['quantity']; ?></td>
        <td class="text-right"><?php echo $product['price']; ?></td>
        <td class="text-right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>

      <?php foreach ($order['total'] as $total) { ?>
      <tr>
        <td class="text-right" colspan="5"><b><?php echo $total['title']; ?></b></td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php break; } ?>
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
    <br>
    <hr>
    <table class="table table-bordered">
      <h5><b>Payment Details</b></h5>
      <thead>
      <tr>
        <td colspan="2">BANK EFT</td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>Beneficiary Account</td>
        <td><b>KWIKBASKET SOLUTIONS LIMITED</b></td>
      </tr>
      <tr>
        <td>Account Type / No.</td>
        <td><b>0100006985957 - KES</b></td>
      </tr>
      <tr>
        <td>Bank Name & Address</td>
        <td>
          <b>STANBIC BANK KENYA LTD</b><br>
          <b>CHIROMO BRANCH</b><br>
          <b>P.O. BOX 72833-00200 NAIROBI</b><br>
          <b>KENYA</b><br>
        </td>
      </tr>
      <tr>
        <td>SWIFT CODE</td>
        <td><b>SBICKENX</b></td>
      </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
      <tr>
        <td>
          LIPA NA MPESA - KWIKBASKET SOLUTIONS LIMITED<br>
          PAYBILL NUMBER: 4029127
        </td>
      </tr>
      </thead>
    </table>
    <br>
    <hr>
    <h5 class="text-center no-margin">KWIKBASKET SOLUTIONS LIMITED</h5><br>
    <p class="text-center no-margin">209/377/1, 3rd Floor, Heritan House, Woodlands Road, Off Argwings Kodhek Road, Opposite DoD Headquarters</p><br>
    <p class="text-center no-margin">Nairobi, 00200 Kenya</p>
  </div>
  <?php } ?>
</div>
<style type="text/css">
  @media print {
    #printPageButton {
      display: none;
    }
  }
  .no-margin {
    margin: 0 !important;
  }
</style>
</body>
</html>