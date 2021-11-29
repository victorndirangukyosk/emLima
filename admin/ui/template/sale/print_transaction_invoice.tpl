<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>

<base href="<?php echo $base; ?>" />


<link href="ui/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="ui/javascript/jquery/jquery-2.1.1.min.js"></script>

<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>

<script type="text/javascript" src="ui/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="ui/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="ui/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <?php foreach ($orders as $order) { ?>
  <div style="page-break-after: always;">
    <h1><?php echo $text_invoice; ?> #<?php echo $order['order_id']; ?></h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <!--   -->
        </tr>
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
            <b><?php echo $text_tax; ?></b> <?php echo $order['store_tax']; ?>
          </td>
          <td style="width: 50%;"><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
            <?php if ($order['invoice_no']) { ?>
            <b><?php echo $text_transaction_id; ?></b> <?php echo $order['invoice_no']; ?><br />
            <?php } ?>
            <b><?php echo $text_description; ?></b> <?php echo $order['comment']; ?><br />
            <b><?php echo $text_tax; ?></b> <?php echo $order['cpf_number']; ?><br />
            <?php if ($order['address']) { ?>
            <b><?php echo $text_address; ?></b> <?php echo $order['address']; ?><br />
            <?php } ?>
            
            </td>
        </tr>
      </tbody>
    </table>
   
    <table class="table table-bordered">
    

      <thead>
        <tr>
          <td><b><?php echo $column_particular; ?></b></td>
          <td><b><?php echo $column_unit; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?> (<?php echo $this->currency->getSymbolLeft() ?>)</b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-right" ><?php echo $post_data['name']; ?></td>
          <td class="text-right"><?php echo $post_data['unit']; ?></td>
          <td class="text-right"><?php echo $post_data['quantity']; ?></td>
          <td class="text-right"><?php echo $post_data['price']; ?></td>
          <td class="text-right"><?php echo ($post_data['price'] * $post_data['quantity']); ?></td>

        
          
        </tr>

        <?php foreach ($post_data['products'] as $key => $pro) { ?>
         <tr>
          <td class="text-right" ><?php echo $pro['name']; ?></td>
            <td class="text-right"><?php echo $pro['unit']; ?></td>
            <td class="text-right"><?php echo $pro['quantity']; ?></td>
            <td class="text-right"><?php echo $pro['price']; ?></td>
            <td class="text-right"><?php echo ($pro['price'] * $pro['quantity']); ?></td>

          
            
        </tr>
              
        <?php } ?>

        <tr rowspan="13">
          <td class="text-right" colspan="5"></td>
        </tr>
        <tr rowspan="13">
          <td class="text-right" colspan="5"></td>
        </tr>
        <tr rowspan="13">
          <td class="text-right" colspan="5"></td>
        </tr>
        <tr rowspan="13">
          <td class="text-right" colspan="5"></td>
        </tr>
        

        <tr>
          <td class="text-right" colspan="3"></td>
          <td class="text-right" >Sub-Total</td>
            <td class="text-right"><?php echo $post_data['sub_total']; ?></td>
        </tr>


        <?php foreach ($post_data['totals'] as $key => $total) { ?>
         <tr>
         <td class="text-right" colspan="3"></td>
          <!-- <td class="text-right" ><?php echo $total['code']; ?></td> -->
            <td class="text-right"><?php echo $total['title']; ?></td>
            <td class="text-right"><?php echo $total['value']; ?></td>
        </tr>
        
        <?php } ?>

         <tr>
         <td class="text-right" colspan="3"></td>
          <td class="text-right" >Total</td>
            <td class="text-right"><?php echo $transaction_details['amount']; ?></td>
        </tr>
        

      </tbody>
    </table>
    
  </div>
  <?php } ?>
</div>

</body>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


</html>