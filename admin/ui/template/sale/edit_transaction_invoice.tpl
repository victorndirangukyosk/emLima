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
          <!-- <td colspan="2"><?php echo $text_order_detail; ?></td> -->
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
    <form id="edit_invoicex" action="<?= $invoicepdf ?>" method="post">
      
    <input type="hidden"  class="form-control" name="transaction_id" value="<?php echo $transaction_id; ?>"/>

    <input type="hidden"  class="form-control" name="sub_total" value="<?php echo $sub_total; ?>" id="subtotal"/>

    <table class="table table-bordered" id="edit_invoice">
    

      <thead>
        <tr>
          <td><b><?php echo $column_particular; ?></b></td>
          <td><b><?php echo $column_unit; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td class="text-right"><b><?php echo $column_price; ?> (<?php echo $this->currency->getSymbolLeft() ?>)</b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-right" ><input type="text" class="form-control" name="name" value="Platform Commission"/></td>
          <td class="text-right"><input type="text" class="form-control" name="unit" value="10%"/></td>
          <td class="text-right">

          <input type="number" min="1" step="1" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" class="form-control changeTotal text-right" name="quantity" value="1"/>

          </td>
          <td class="text-right"><input type="text"  class="form-control changeTotal text-right" name="price" value="<?php echo $transaction_details['amount']; ?>"/></td>
          <td class="text-right"><input type="text" class="form-control totalPrice text-right" name="total" value="<?php echo $transaction_details['amount']; ?>" disabled /></td>

          <td>

            <button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>
            <!-- <a class="remove" style="color:red;cursor: pointer;"> X </a>  -->
          </td>
          
        </tr>
        
        <tr class="productsAdd">
          <td colspan="5">
          </td>
          <td>
              <button type="button" onclick="add();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add Product"><i class="fa fa-plus-circle"></i></button>
          </td>
        </tr>

        <?php foreach ($order['total'] as $total) { ?>
        <tr>


          
          <?php if($total['code'] == 'sub_total') { ?>

            <td class="text-right" colspan="4">
              <b><input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>" disabled /></b>
            </td>
            <td class="text-right">
              <input type="text"  class="form-control" name="totals[<?php echo $total['code']; ?>][value]"  id="sub_total" value="<?php echo $total['text']; ?>" disabled="" />
            </td>
          <?php } elseif($total['code'] == 'total') { ?>


              
              </tr>
              <tr>
                <td colspan="5">
                </td>
                <td>
                    <button type="button" onclick="addInBetween();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add In Order Total"><i class="fa fa-plus-circle"></i></button>
                </td>
              </tr>
              <tr>
                <td class="text-right" colspan="4">
                <b><input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>" disabled /></b>
              </td>
              <td class="text-right">
                <input type="text"  class="form-control" name="totals[<?php echo $total['code']; ?>][value]" id="total" value="<?php echo $total['text']; ?>" disabled="" />
              </td>
              </tr>
          <?php } else { ?>

          

              <td class="text-right" colspan="2">
                <select name="codes" id="input-customer-group" class="form-control">
                  <?php foreach ($allCodes as $allCode) { ?>
                  <?php if ($allCode['code'] == $total['code']) { ?>
                  <option value="<?php echo $allCode['code']; ?>" selected="selected"><?php echo $allCode['code']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $allCode['code']; ?>"><?php echo $allCode['code']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

                <input type="hidden"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][code]" value="<?php echo $total['code']; ?>"/>
              </td>

              <td class="text-right" colspan="2">
                <b>
                <input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>"/> </b>
              </td>
              <td class="text-right">

                <input type="text"  class="form-control inBetween changeTotalValue" name="totals[<?php echo $total['code']; ?>][value]" value="<?php echo $total['text']; ?>"/>
              </td>

              <td>
                  <button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>
              </td>
          <?php } ?> 
          
        
        <?php } ?>
      </tbody>
    </table>
    
    
  </div>
  <?php } ?>
</div>

<div class="text-center">
  <button type="submit" class="btn btn-primary" id="button-edit-invoice" value="Save"><i class="fa fa-save"></i> Print PDF</button>
  <a href="<?= $sale_order_link ?>" id="back_button">

  <button type="button" class="btn btn-lg btn-primary" value="Save" style="
    position: fixed;
    top: 0;
    right: 0;
"><i class="fa fa-return"></i> Back </button></a>

<!-- <a href="<?php echo $invoicepdf; ?>" id="btn-invoicepdf" data-toggle="tooltip" class="btn btn-default"><i class="fa fa-file-pdf-o"></i></a> -->







</div>

</form>  
</body>

<script type="text/javascript">
  
$(document).delegate('.remove','click', function(){
  $(this).parent().parent().remove();
  $(".changeTotal").trigger("blur");
  $(".changeTotalValue").trigger("blur");
});


$('#button-edit-invoicex').on('click', function() {


  if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }
  }



  $.ajax({
    url: 'index.php?path=sale/order/PrintTransactionInvoice&token=<?php echo $token; ?>&transaction_id=<?php echo $transaction_id; ?>',
    type: 'post',
    dataType: 'json',
    data: $('#edit_invoicex').serialize(),
    beforeSend: function() {
      $('#button-history').button('loading');     
    },
    complete: function() {
      $('#button-history').button('reset'); 
    },
    success: function(json) {
      console.log(json);
      if (json['status']) {
        //alert('Updated successfully!!'); 

        //location = location;
        //window.location.href = 'index.php?path=sale/order&token=<?php echo $token; ?>';
      } else {

      }   
    },      
    error: function(xhr, ajaxOptions, thrownError) {
    }
  });
});

$(document).delegate('.changeTotal','change', function() {

  console.log("change");
  console.log($(this).val());

  var q = $(this).parent().parent().children().eq(2).children().val();
  var p = $(this).parent().parent().children().eq(3).children().val();

  $(this).parent().parent().children().eq(4).children().val(p*q);

  var sum =0;
  var inbetweensum =0;
  $('.totalPrice').each(function() {
    console.log("value");

    console.log(this.value);

    sum += Number($(this).val());
  });

  $('.inBetween').each(function() {
    console.log("inBetween");

    console.log(this.value);

    inbetweensum += Number($(this).val());
  });


  

  $('#sub_total').val(sum);
  $('#subtotal').val(sum);

  $('#total').val(inbetweensum + Number(sum));

});

$(document).delegate('.changeTotalValue','blur', function() {

  console.log("changeTotalValue");
  

  var sum =0;
  var inbetweensum =0;
  $('.totalPrice').each(function() {
    console.log("value");

    console.log(this.value);

    sum += Number($(this).val());
  });

  $('.inBetween').each(function() {
    console.log("inBetween");

    console.log(this.value);

    inbetweensum += Number($(this).val());
  });


  

  $('#sub_total').val(sum);

  $('#total').val(inbetweensum + Number(sum));

});

$('#button-settle-invoice-charge').on('click', function() {
  // update invoice call this
  if(!confirm('Are you sure?')) {
    return false;
  }

  if($('#total').val() < "<?= $this->config->get('stripe_order_total') ?>" ) {
    alert('Minimum order amount value required is : '+"<?= $this->config->get('stripe_order_total') ?>");
    return false;
  }

  if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }
  }
});


function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

function add() {


  noProduct = makeid();

  $html  = '<tr>';        
  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control" name="products['+noProduct+'][name]" value=""/>';
  
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control" name="products['+noProduct+'][unit]" value=""/>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="number" min="1" step="1" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" class="form-control changeTotal text-right" name="products['+noProduct+'][quantity]" value="1"/>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control changeTotal text-right" name="products['+noProduct+'][price]" value=""/>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control totalPrice text-right" name="products['+noProduct+'][total]" value="" disabled/>';
  $html += '</td>';

  $html += '<td>';
  $html += '<button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>  <input type="hidden" name="products['+noProduct+'][product_id]" value=""/>';
  $html += '</td>';

  $html += '</tr>';
  
  $('.productsAdd').before($html);
    
  run(noProduct);
}

function addInBetween() {


  titleCode = makeid();


  $html  = '<tr>';        

  $html += '<td class="text-right" colspan="2"><select name="totals['+titleCode+'][code]" id="input-customer-group" class="form-control">';
  <?php foreach ($allCodes as $allCode) { ?>
   $html += '<option value="<?php echo $allCode['code']; ?>"><?php echo $allCode['code']; ?></option>';
   <?php } ?>

   $html += '</select></td>';



  $html += '<td class="text-right" colspan="2"><b>';
  $html += '<input type="text"  class="form-control inBetweenTitle" name="totals['+titleCode+'][title]" value=""/>';
  $html += '</b></td>';
  
  $html += '<td>';
  $html += '<input type="text"  class="form-control inBetween changeTotalValue" name="totals['+titleCode+'][value]" value=""/>';
  $html += '</td>';

  $html += '<td>';
  $html += '<button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>';
  $html += '</td>';

  $html += '</tr>';
  
  $('#edit_invoice tbody > tr:nth-last-child(3)').after($html);
    
}


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <script type="text/javascript">

    $( "#input-order-status-uni" ).select2({
        theme: "classic",
         width: 'resolve'
    });
    
    function run(noProduct) {
        
      console.log("sd");
      //'input[name=\'product_name\']'
      $('input[name=\'products['+noProduct+'][name]\']').autocomplete({
        'source': function(request, response) {                

            console.log("sd source");
            console.log(request);
            console.log(response);

            $.ajax({
                url: 'index.php?path=sale/order/product_autocomplete&order_id=<?php echo $order_id; ?>&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',     
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name']+' - '+item['unit'],
                            value: item['name'],
                            unit: item['unit'],
                            price: item['price'],
                            product_id: item['product_store_id'],
                        }
                    }));

                    console.log(json);
                }
            });

            //$('.product_name').val(request.term);
        },
        //'select': function(item) {
        select: function (event, ui) {
          console.log("ui");
          console.log(ui);
          //console.log(item['label']);
            //$('.product_name').val(item['label']).focus();
            //$('.product_name').val(item['value']);
            
            $('input[name=\'products['+noProduct+'][unit]').val(ui.item.unit);
            $('input[name=\'products['+noProduct+'][price]').val(ui.item.price);
            $('input[name=\'products['+noProduct+'][total]').val(ui.item.price);
            $('input[name=\'products['+noProduct+'][name]').val(ui.item.label);
            $('input[name=\'products['+noProduct+'][product_id]').val(ui.item.product_id);
            
            $( ".changeTotal" ).change();

        } 
      });
    }


</script>

</html>