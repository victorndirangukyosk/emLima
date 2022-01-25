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
<div class="container" style="width:1300px !important;">
  <?php foreach ($orders as $order) { ?>
  <input type="hidden" id="order_status_id" name="order_status_id" value="<?php echo $order['order_status_id']; ?>" />
  <div style="page-break-after: always;">
    <h1><?php echo $text_invoice; ?> #<?php echo $order['order_id']; ?></h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td colspan="2"><?php echo $text_order_detail; ?></td>
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

    <?php if (!$this->user->isVendor()): ?>
                        
               
      <table class="table table-bordered">
        <thead>
          <tr>
            <td style="width: 50%;"><b><?php echo $text_to; ?></b></td>
            <td style="width: 50%;"><b><?php echo $text_ship_to; ?></b></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <address>
              <b><?= $text_name ?></b> <?php echo $order['shipping_name']; ?>, <br />
              <b><?= $text_contact_no ?></b> <?php echo $order['shipping_contact_no']; ?><br/>
			  <?php if($order['cpf_number']){?>
              <b><?php echo $text_cpf_number; ?></b> <?php echo $order['cpf_number']; ?>
			  <?php }?>
              </address>
            </td>
            <td>
              <address>
              <?php echo $order['shipping_address'] ?><br />
              <?php echo $order['shipping_city']; ?>            
              </address>
            </td>
          </tr>
        </tbody>
      </table>
     <?php endif ?>

    <form id="edit_invoicex">

      
    <table class="table table-bordered" id="edit_invoice">
          <b><?php echo 'P.O. Number : ' ?></b> 
          <input type="text" class="" style="width:250px;border-color: grey !important;border-width: thin;border-radius: 5px;" name="po_number" value="<?php echo $order['po_number']; ?>"/>
    
</br>
</br>

      <thead>
        <tr>
          <td style="width: 15%;" ><b><?php echo $column_product; ?></b></td>
          <td style="width: 15%;" ><b>Product Notes</b></td> 
          <!--<td style="width: 15%;" ><b><?php echo $column_produce_type; ?></b></td>--> 
          <td style="width: 9%;"><b><?php echo $column_unit; ?></b></td>
          <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
          <td style="width: 10%;"><b><?php echo $column_unit_update; ?></b></td>
          <td class="text-right"><b>Quantity ( Missed )</b></td>
          <td class="text-right"><b><?php echo $column_quantity_update; ?></b></td>
          <td style="width: 8%;" class="text-right"><b><?php echo $column_price; ?> (<?php echo $this->currency->getSymbolLeft() ?>)</b></td>
          <td class="text-right"><b><?php echo $column_total; ?></b></td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order['product'] as $product) { ?>
        <tr>
          <td class="text-right" ><input type="text" class="form-control" name="products[<?php echo $product['product_id']?>][name]" value="<?php echo $product['name']; ?>"/></td>
          <td class="text-right"><input type="hidden" class="form-control"  disabled name="products[<?php echo $product['product_id']?>][produce_type]" placeholder='-' value="<?php if($product['produce_type']<>null){echo $product['produce_type'];}?>"/><input type="text" class="form-control" name="products[<?php echo $product['product_id']?>][product_note]" placeholder='Product Notes' value="<?php if($product['product_note']<>null){echo $product['product_note'];}?>"/></td>
          <td class="text-right"><input type="text" class="form-control"  disabled name="products[<?php echo $product['product_id']?>][unit]" value="<?php echo $product['unit']; ?>"/></td>
          <td class="text-right">

          <!--<input type="number" min="1" step="1" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" class="form-control changeTotal text-right" name="products[<?php echo $product['product_id'] ?>][quantity]" value="<?php echo $product['quantity']; ?>"/>-->
          <input type="number" disabled min="1" step="1" class="form-control  text-right" name="products[<?php echo $product['product_id'] ?>][quantity]" value="<?php echo $product['quantity']+$product['missed_quantity']; ?>"/>
          </td>
		  <td class="text-right">
                      <select name="products[<?php echo $product['product_id']?>][unit]" class="form-control changeUnit" data-product_id="<?php echo $product['product_id']?>">
                          <?php foreach($product['variations'] as $variant) { ?>
                          <?php if($variant['variation_id'] == $product['product_id']) { ?>
                          <option data-model="<?php echo $variant['model'] ?>" data-categoryprice="<?php echo $variant['category_price'] ?>" data-price="<?php echo $variant['price'] ?>" data-special="<?php echo $variant['special_price'] ?>" data-product_id="<?php echo $variant['variation_id'] ?>" <?php echo $variant['category_price_variant'] ?> selected><?php echo $variant['unit']; ?></option>
                          <?php } else { ?>
                          <option data-model="<?php echo $variant['model'] ?>" data-categoryprice="<?php echo $variant['category_price'] ?>" data-price="<?php echo $variant['price'] ?>" data-special="<?php echo $variant['special_price'] ?>" data-product_id="<?php echo $variant['variation_id'] ?>" <?php echo $variant['category_price_variant'] ?> ><?php echo $variant['unit']; ?></option>
                          <?php } } ?>
                      </select>
                      <!--<input type="text" class="form-control" name="products[<?php echo $product['product_id']?>][unit]" value="<?php echo $product['unit']; ?>"/>-->
                  </td>
          <td class="text-right">

          <!--<input type="number" min="1" step="1" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" class="form-control changeTotal text-right" name="products[<?php echo $product['product_id'] ?>][quantity]" value="<?php echo $product['quantity']; ?>"/>-->
          <input type="number" min="0" step="1" class="form-control changeTotal changeMissedQuantity text-right" name="products[<?php echo $product['product_id'] ?>][quantity_missed]" value="<?php echo $product['missed_quantity']; ?>"/>
          </td>        
          <td class="text-right">

          <!--<input type="number" min="1" step="1" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" class="form-control changeTotal text-right" name="products[<?php echo $product['product_id'] ?>][quantity]" value="<?php echo $product['quantity']; ?>"/>-->
          <input type="number" min="1" step="1" class="form-control changeTotal changeQuantity text-right" name="products[<?php echo $product['product_id'] ?>][quantity]" value="<?php echo $product['quantity']; ?>"/>
          </td>
		  
          <td class="text-right"><input type="text"  class="form-control changeTotal text-right" name="products[<?php echo $product['product_id'] ?>][price]" value="<?php echo $product['price']; ?>"/></td>
          <td class="text-right"><input type="text" class="form-control totalPrice text-right" name="products[<?php echo $product['product_id']?>][total]" value="<?php echo $product['total']; ?>" disabled /></td>

          <td>

            <button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>
            <input type="hidden" name="products[<?php echo $product['product_id']?>][product_id]" value="<?php echo $product['product_id']?>"/>

            <input type="hidden" name="products[<?php echo $product['product_id']?>][model]" value="<?php echo $product['model']?>"/>
            <input type="hidden" name="products[<?php echo $product['product_id']?>][product_note]" value="<?php echo $product['product_note']?>"/>
            <input type="hidden" name="products[<?php echo $product['product_id']?>][produce_type]" value="<?php echo $product['produce_type']?>"/>

            <!-- <a class="remove" style="color:red;cursor: pointer;"> X </a>  -->
          </td>
          
        </tr>

        <?php } ?>
        
        <tr class="productsAdd">
          <td colspan="9">
          </td>
          <td>
              <button type="button" onclick="add();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add Product"><i class="fa fa-plus-circle"></i></button>
          </td>
        </tr>

        <?php foreach ($order['total'] as $total) { ?>
        <tr>


          
          <?php if($total['code'] == 'sub_total') { ?>

            <td class="text-right" colspan="8">
              <b><input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>" disabled /></b>
            </td>
            <td class="text-right">
              <input type="text"  class="form-control" name="totals[<?php echo $total['code']; ?>][value]"  id="sub_total" value="<?php echo $total['text']; ?>" disabled="" />
            </td>
           <!-- shipping_custom ADDED PREVIOUSLY IT IS shipping -->
          <?php } elseif($total['code'] == 'shipping_custom' && $total['text']>0) { ?>
  
            <td class="text-right" colspan="4">
              <b><input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>" disabled /></b>
            </td>
            <td class="text-right">
              <input type="text"  class="form-control" name="totals[<?php echo $total['code']; ?>][value]"  id="sub_total" value="<?php echo $total['text']; ?>" disabled="" />
            </td>
          <?php } 
          
          elseif($total['code'] == 'total') { ?>


              
              </tr>
              <tr style="display: none;">
                <td colspan="5">
                </td>
                <td>
                    <button type="button" onclick="addInBetween();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add In Order Total"><i class="fa fa-plus-circle"></i></button>
                </td>
              </tr>
              <tr style="display: none;">
                <td class="text-right" colspan="4">
                <b><input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>" disabled /></b>
              </td>
              <td class="text-right">
                <input type="text"  class="form-control" name="totals[<?php echo $total['code']; ?>][value]" id="total" value="<?php echo $total['text']; ?>" disabled="" />
              </td>
              </tr>
          <?php } else { ?>

          

              <td class="text-right" colspan="2" style="display: none;">
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

              <td class="text-right" colspan="2" style="display: none;">
                <b>
                <input type="text"  class="form-control inBetweenTitle" name="totals[<?php echo $total['code']; ?>][title]" value="<?php echo $total['title']; ?>"/> </b>
              </td>
              <td class="text-right" style="display: none;">

                <input type="text"  class="form-control inBetween changeTotalValue" name="totals[<?php echo $total['code']; ?>][value]" value="<?php echo $total['text']; ?>"/>

                <input type="hidden"  class="" name="totals[<?php echo $total['code']; ?>][actual_value]" value="<?php echo $total['actual_value']; ?>"/>
                
              </td>

              <td style="display: none;">
                  <button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>
              </td>
          <?php } ?> 
          
        
        <?php } ?>
      </tbody>
    </table>
    </form>
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

<div class="text-center">
  <!-- <button type="submit" class="btn btn-primary" id="button-edit-invoice" value="Save"><i class="fa fa-save"></i> Update</button> -->
  <a href="<?= $sale_order_link ?>" id="back_button">

  <button type="button" class="btn btn-lg btn-primary" value="Save" style="
    position: fixed;
    top: 0;
    right: 0;
"><i class="fa fa-return"></i> Back </button></a>


<?php if($settlement_tab) { ?>

    <button type="submit" class="btn btn-lg btn-success" id="button-settle-invoice-charge" data-loading-text="Wait..." value="Save" style="
    position: fixed;
    top: 60px;
    right: 0;
"  <?php if($settlement_done) { ?> disabled="true" <?php } ?> ><i class="fa fa-check-square"></i> Update and Save </button>
  

  <button type="submit" class="btn btn-lg btn-success" id="button-settle-invoice-charge-notify" data-loading-text="Wait..." value="Save" style="
    position: fixed;
    top: 120px;
    right: 0;
"  <?php if($settlement_done) { ?> disabled="true" <?php } ?> ><i class="fa fa-envelope"></i> Notify </button>


<?php } ?>

<!-- <div>
  <?php if($settlement_tab) { ?>

  
    <button type="submit" class="btn btn-lg btn-danger" id="button-settle-invoice-refund" value="Save" style="
    position: fixed;
    top: 60px;
    right: 0;
"  <?php if($settlement_done) { ?> disabled="true" <?php } ?> ><i class="fa fa-rotate-left"></i> Refund </button>


<?php } ?>
</div> -->








</div>
</body>

<script type="text/javascript">
  
$(document).delegate('.remove','click', function(){
  if($('#order_status_id').val() == 5) {
   alert('Delivered Orders Not Editable!');    
   return false;
  }   
  $(this).parent().parent().remove();
  $(".changeTotal").trigger("blur");
  $(".changeTotalValue").trigger("blur");
});

$(document).delegate('.changeMissedQuantity','change', function() {
console.log("changeMissedQuantity");
$(this).parent().parent().children().eq(6).children().val($(this).parent().parent().children().eq(3).children().val() - $(this).parent().parent().children().eq(5).children().val());
});

$(document).delegate('.changeQuantity','change', function() {
console.log("changeQuantity");    
$(this).parent().parent().children().eq(3).children().val($(this).val());
$(this).parent().parent().children().eq(6).children().val($(this).parent().parent().children().eq(3).children().val() - $(this).parent().parent().children().eq(5).children().val());
});



$(document).delegate('.changeTotal','change', function() {

  console.log("change");
  console.log($(this).val());

  var q = $(this).parent().parent().children().eq(6).children().val();
  var p = $(this).parent().parent().children().eq(7).children().val();

  $(this).parent().parent().children().eq(8).children().val(p*q);

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

$(document).delegate('.changeUnit','change', function() {
  
  console.log("changeUnit");
  console.log($(this).val());
  console.log($(this).find(':selected').attr('data-price'));
  console.log($(this).find(':selected').attr('data-product_id')+'data-product_id');
  var old_product_id = $(this).attr('data-product_id');
  var new_product_id = $(this).find(':selected').attr('data-product_id');
  $(this).attr("data-product_id", new_product_id);

  var q = $(this).parent().parent().children().eq(6).children().val();
  var p = $(this).parent().parent().children().eq(7).children().val();
  var qo = $(this).parent().parent().children().eq(3).children().val();
  var uo = $(this).parent().parent().children().eq(2).children().val();
  
  //Assign Values
  $(this).parent().parent().children().eq(2).children().val($(this).val());
  if($(this).find(':selected').attr('data-categoryprice').toString().replace(/,/g, '') > 0) {
  $(this).parent().parent().children().eq(7).children().val($(this).find(':selected').attr('data-categoryprice').toString().replace(/,/g, ''));
  $(this).parent().parent().children().eq(8).children().val($(this).find(':selected').attr('data-categoryprice').toString().replace(/,/g, '')*q);
  } else if($(this).find(':selected').attr('data-special').toString().replace(/,/g, '') > 0) {
  $(this).parent().parent().children().eq(7).children().val($(this).find(':selected').attr('data-special').toString().replace(/,/g, ''));
  $(this).parent().parent().children().eq(8).children().val($(this).find(':selected').attr('data-special').toString().replace(/,/g, '')*q);
  } else {
  $(this).parent().parent().children().eq(7).children().val($(this).find(':selected').attr('data-price').toString().replace(/,/g, ''));
  $(this).parent().parent().children().eq(8).children().val($(this).find(':selected').attr('data-price').toString().replace(/,/g, '')*q);    
  }
  //$(this).parent().parent().children().eq(9).children().val($(this).find(':selected').attr('data-product_id'));
  
  $(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][product_id]"]').val($(this).find(':selected').attr('data-product_id'));
  $(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][model]"]').val($(this).find(':selected').attr('data-model'));
  
  
  $(this).parent().parent().children().eq(0).children('input[name="products['+old_product_id+'][name]"]').attr('name', 'products['+new_product_id+'][name]');
  $(this).parent().parent().children().eq(1).children('input[name="products['+old_product_id+'][produce_type]"]').attr('name', 'products['+new_product_id+'][produce_type]');
  $(this).parent().parent().children().eq(1).children('input[name="products['+old_product_id+'][product_note]"]').attr('name', 'products['+new_product_id+'][product_note]');
  $(this).parent().parent().children().eq(2).children('input[name="products['+old_product_id+'][unit]"]').attr('name', 'products['+new_product_id+'][unit]');
  $(this).parent().parent().children().eq(3).children('input[name="products['+old_product_id+'][quantity]"]').attr('name', 'products['+new_product_id+'][quantity]');
  $(this).parent().parent().children().eq(4).children('select[name="products['+old_product_id+'][unit]"]').attr('name', 'products['+new_product_id+'][unit]');
  $(this).parent().parent().children().eq(6).children('input[name="products['+old_product_id+'][quantity]"]').attr('name', 'products['+new_product_id+'][quantity]');
  $(this).parent().parent().children().eq(7).children('input[name="products['+old_product_id+'][price]"]').attr('name', 'products['+new_product_id+'][price]');
  
  $(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][product_id]"]').attr('name', 'products['+new_product_id+'][product_id]');
  $(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][model]"]').attr('name', 'products['+new_product_id+'][model]');
  $(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][product_note]"]').attr('name', 'products['+new_product_id+'][product_note]');
  $(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][produce_type]"]').attr('name', 'products['+new_product_id+'][produce_type]');
  
  console.log($(this).parent().parent().children().eq(9).children('input[name="products['+old_product_id+'][product_id]"]').val($(this).find(':selected').attr('data-product_id')));

  
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
  
  console.log($(this).find(':selected').attr('data-price').replace(/,/g, '')+'  '+q);
  console.log(q);
  console.log(p);
  console.log(qo);
  console.log(uo);  
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


$('#button-settle-invoice-refund').on('click', function() {

  if(!confirm('Are you sure ?')) {
    return false;
  }


  if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }
  }

  $.ajax({
    url: 'index.php?path=sale/editinvoice/updateInvoice&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&customer_id=<?php echo $customer_id; ?>&settle=1&charge=0',
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
        alert('Refunded successfully!!'); 

        location = location;
      }     
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      location = location;
    }
  });
});

$('#button-settle-invoice-charge').on('click', function() {
   if($('#order_status_id').val() == 5) {
   alert('Delivered Orders Not Editable!');    
   return false;
   } 
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

  $.ajax({
    url: 'index.php?path=sale/editinvoice/updateInvoice&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&customer_id=<?php echo $customer_id; ?>&settle=1&charge=1',
    type: 'post',
    dataType: 'json',
    data: $('#edit_invoicex').serialize(),
    beforeSend: function() {
      $('#button-history').button('loading');  
      $('#button-settle-invoice-charge').button('loading');

      $('#back_button').hide(); 
    },
    complete: function() {
      $('#button-history').button('reset'); 
      $('#button-settle-invoice-charge').button('reset');

      $('#back_button').show(); 
    },
    success: function(json) {
      console.log(json);
      console.log("json char");
      if (json['status']) {
        alert('Updated successfully!!');
        // window.location.href = 'index.php?path=sale/order&token=<?php echo $token; ?>';
        window.history.back();
      } else {
        if (json['message']) {
          alert(json['message']);
        } else {
          alert('Something went wrong!!');
        }
        location = location;

      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('#button-settle-invoice-charge-notify').on('click', function() {
  // update invoice call this
  

  $.ajax({
    url: 'index.php?path=sale/order/notifyInvoice&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&customer_id=<?php echo $customer_id; ?>&settle=1&charge=1',
    type: 'post',
    dataType: 'json',
    data: $('#edit_invoicex').serialize(),
    beforeSend: function() {
      $('#button-history').button('loading');  
      $('#button-settle-invoice-charge-notify').button('loading');

      $('#button-settle-invoice-charge').hide(); 

      $('#back_button').hide(); 
    },
    complete: function() {
      $('#button-history').button('reset'); 
      $('#button-settle-invoice-charge-notify').button('reset');

      $('#back_button').show(); 
      $('#button-settle-invoice-charge').show(); 
    },
    success: function(json) {
      console.log(json);
      console.log("json char");
      if (json['status']) {
        alert('Notified!!');
        window.location.href = 'index.php?path=sale/order&token=<?php echo $token; ?>';
      } else {
        if (json['message']) {
          alert(json['message']);
        } else {
          alert('Something went wrong!!');
        }
        location = location;

      }
    },      
    error: function(xhr, ajaxOptions, thrownError) {
	 alert('Notified!!');
	 window.location.href = 'index.php?path=sale/order&token=<?php echo $token; ?>';
     //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});



$('#button-edit-invoice').on('click', function() {


  if(typeof verifyStatusChange == 'function'){
    if(verifyStatusChange() == false){
      return false;
    }
  }



  $.ajax({
    url: 'index.php?path=sale/editinvoice/updateInvoice&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&settle=0',
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
        alert('Updated successfully!!'); 

        //location = location;
        window.location.href = 'index.php?path=sale/order&token=<?php echo $token; ?>';
      } else {

      }   
    },      
    error: function(xhr, ajaxOptions, thrownError) {
    }
  });
});

function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

function add() {

  if($('#order_status_id').val() == 5) {
   alert('Delivered Orders Not Editable!');    
   return false;
  }  
  noProduct = makeid();

  $html  = '<tr>';        
  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control" name="products['+noProduct+'][name]" value=""/>';
  
  $html += '</td>';
   $html += '<td class="text-right">';
  $html += '<input type="hidden" class="form-control" disabled  name="products['+noProduct+'][produce_type]" value="-"/><input type="text" class="form-control"  name="products['+noProduct+'][product_note]" value="" placeholder="Product Notes"/>';
  
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control" disabled name="products['+noProduct+'][unit]" value=""/>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="number" min="1" step="1" disabled class="form-control changeTotal text-right" name="products['+noProduct+'][quantity]" value="1"/>';
  $html += '</td>';
  
  $html += '<td class="text-right">';
  /*$html += '<input type="text" class="form-control" name="products['+noProduct+'][unit]" value=""/>';*/
  $html += '<select name="products['+noProduct+'][unit]" data-product_id="'+noProduct+'" class="form-control changeUnit"></select>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="number" min="0" step="1"  class="form-control changeTotal changeMissedQuantity text-right" name="products['+noProduct+'][quantity_missed]" value="0"/>';
  $html += '</td>';
  
  $html += '<td class="text-right">';
  $html += '<input type="number" min="1" step="1"  class="form-control changeTotal changeQuantity text-right" name="products['+noProduct+'][quantity]" value="1"/>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control changeTotal text-right" name="products['+noProduct+'][price]" value=""/>';
  $html += '</td>';

  $html += '<td class="text-right">';
  $html += '<input type="text" class="form-control totalPrice text-right" name="products['+noProduct+'][total]" value="" disabled/>';
  $html += '</td>';

  $html += '<td>';
  $html += '<button type="button" data-toggle="tooltip" title="" class="btn btn-danger remove" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>  <input type="hidden" name="products['+noProduct+'][product_id]" value=""/> <input type="hidden" name="products['+noProduct+'][model]" value=""/>';
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
                        console.log(item['category_price'].toString().replace(/,/g, ""));
                        if(item['category_price'].toString().replace(/,/g, "") > 0) {
                        return {
                            label: item['name']+' - '+item['unit'],
                            value: item['name'],
                            unit: item['unit'],
                            price: item['category_price'],
                            model: item['model'],
                            product_id: item['product_store_id'],
                        }
                    } else if(item['special_price'].toString().replace(/,/g, "") > 0){
                      return {
                            label: item['name']+' - '+item['unit'],
                            value: item['name'],
                            unit: item['unit'],
                            price: item['special_price'],
                            model: item['model'],
                            product_id: item['product_store_id'],
                        }  
                    } else {
                    return {
                            label: item['name']+' - '+item['unit'],
                            value: item['name'],
                            unit: item['unit'],
                            price: item['price'],
                            model: item['model'],
                            product_id: item['product_store_id'],
                        }    
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
          console.log(ui.item.product_id);
          console.log(ui.item.price);

          $.ajax({
                url: 'index.php?path=sale/order/getProductVariantsInfo&order_id=<?php echo $order_id; ?>&product_store_id='+ui.item.product_id+'&token=<?php echo $token; ?>',
                dataType: 'json',     
                success: function(json) {
                    console.log(json);
                    var option = '';
                    for (var i=0;i<json.length;i++){
                           option += '<option data-model="'+ json[i].model +'" data-product_id="'+ json[i].product_store_id +'" data-categoryprice="'+ json[i].category_price +'" data-price="'+ json[i].price +'" data-special="'+ json[i].special_price +'" value="'+ json[i].unit + '"  '+ json[i].category_price_variant + '>' + json[i].unit + '</option>';
                    }
                    console.log(option);
                    $('select[name=\'products['+noProduct+'][unit]').append(option);
                }
            });
          
          //console.log(item['label']);
            //$('.product_name').val(item['label']).focus();
            //$('.product_name').val(item['value']);
            $('input[name=\'products['+noProduct+'][unit]').val(ui.item.unit);
            $('input[name=\'products['+noProduct+'][price]').val(ui.item.price);
            $('input[name=\'products['+noProduct+'][total]').val(ui.item.price);
            $('input[name=\'products['+noProduct+'][name]').val(ui.item.label);
            $('input[name=\'products['+noProduct+'][product_id]').val(ui.item.product_id);
            $('input[name=\'products['+noProduct+'][model]').val(ui.item.model);
            
            $( ".changeTotal" ).change();

        } 
      });
    }
    

</script>

</html>