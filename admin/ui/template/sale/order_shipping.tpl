<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KwikBasket Delivery Note</title>
  <link rel="stylesheet" href="ui/stylesheet/bootstrap.min.css">
  <link rel="stylesheet" href="ui/javascript/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="ui/stylesheet/print.css">
</head>

<body>
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
                  </ul>
                </div>
              </div>
              <div class="col-md-4 offset-md-4 text-right">
                <h4 class="bold">DELIVERY NOTE</h4>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4">
                <h5 class="bold text-uppercase mb-1">TO <?= $customer_company_name ?></h5>
                <ul class="list-block mb-2">
                  <li>
                    <p class="bold"><?= $shipping_address ?></p>
                  </li>
                </ul>
                <ul class="list-block">
                  <li><?= $shipping_name ?></li>
                  <li><?= $telephone ?></li>
                  <li class="mb-2"><?= $email ?></li>
                </ul>

              </div>

              <div class="col-md-4 offset-md-4 text-right">
                <h4 class="bold mb-3">ORDER #<?= $order_id ?></h4>
                <ul class="list-block">
                  <?php if($po_number) { ?>
                  <li>P.O Number <?= $po_number ?></li>
                  <?php }?>
                  <li>Placed On <?= $date_added ?></li>
                  <li>Delivered On <?= $delivery_date ?></li>
                  <li><?= $shipping_method ?></li>                 
                  <li><?= $payment_method ?></li>
                </ul>
              </div>
            </div>

            <table class="datatable">
              <thead class="datatable-header">
              <tr>
                <td>SKU</td>
                <td>Product</td>
                <td class="text-center">Quantity Ordered</td>
                <td class="text-center">Quantity Delivered</td>
                <td class="text-right">Unit Price</td>
                <td class="text-right">Total</td>
              </tr>
              </thead>
              <tbody class="datatable-content">
              <?php foreach($products as $product) { ?>
              <tr>
                <td><?= $product['product_id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td class="text-center"><?= $product['quantity'] ?> <?= $product['unit'] ?></td>
                <td class="text-center"><?= $product['quantity_updated'] ?> <?= $product['unit_updated'] ?></td>
                <td class="text-right">
                  <div class="price-container">
                    <span class="currency">
                      <?= $product['price_currency'] ?>
                    </span>
                    <span class="value">
                      <?= $product['price_value'] ?>
                    </span>
                  </div>
                </td>
                <td class="text-right">
                  <div class="price-container">
                    <span class="currency">
                      <?= $product['total_updated_currency'] ?>
                    </span>
                    <span class="value">
                      <?= $product['total_updated_value'] ?>
                    </span>
                  </div>
                </td>
              </tr>
              <?php } ?>

              <?php foreach($totals as $total) { ?>
              <tr>
                <td colspan="5" class="bold text-right"><?= $total['title'] ?></td>
                <td class="bold text-right"><?= $total['text'] ?></td>
              </tr>
              <?php } ?>
              </tbody>
            </table>

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
                    <li>Beneficiary Account: KWIKBASKET SOLUTIONS LIMITED</li>
                    <li>Account Type: KES</li>
                    <li>Account Number: 0100006985957</li>
                    <li>Bank Name: STANBIC BANK KENYA LTD</li>
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
                    <li>Enter <strong><?= $customer_company_name ?></strong> as the account number</li>
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