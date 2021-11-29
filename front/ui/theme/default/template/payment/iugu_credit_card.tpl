<!--
	Author: Valdeir Santana
	Site: http://www.valdeirsantana.com.br
	License: http://www.gnu.org/licenses/gpl-3.0.en.html
-->
<style>
/* entire container, keeps perspective */
.flip-container {
	perspective: 1000;
	transform-style: preserve-3d;
  height:180px
}
	/*  UPDATED! flip the pane when hovered */
	.flip-container-hover .back {
		transform: rotateY(0deg);
	}
	.flip-container-hover .front {
	    transform: rotateY(180deg);
	}

.flip-container, .front, .back {
	height: 180px;
}

/* flip speed goes here */
.flipper {
	transition: 0.6s;
	transform-style: preserve-3d;

	position: relative;
}

/* hide back of pane during swap */
.front, .back {
	backface-visibility: hidden;
	transition: 0.6s;
	transform-style: preserve-3d;

	position: absolute;
	top: 0;
	left: 0;
}

/*  UPDATED! front pane, placed above back */
.front {
	z-index: 2;
	transform: rotateY(0deg);
}

/* back, initially hidden pane */
.back {
	transform: rotateY(-180deg);
}

.logo-iugu {
  position: absolute;
  bottom: -200px;
  right: 27px;
}
</style>

<div class="container-fluid" id="wrapper-iugu">

<div class="col-sm-12" id="iugu_payment_methods">

<?php if(count($payment_methods) > 0) { ?>
  
        <?php foreach($payment_methods as $method) { ?>
          <div class="col-md-6">
            <div class="address-block" >
                <h3 class="address-locations">
                  <?= $method['brand'] ?>
                </h3>
                <h4 class="address-name"><?= $method['display_number'] ?></h4>
                <p><?= $method['holder_name'] ?>
                    <br><?= $method['description'] ?>
                </p>

                <button type="button" data-payment-method-id="<?= $method['payment_method_id'] ?>" class="collapsed btn btn-primary btn-block iugu-pay">
                  <span class="button-pay-text"><?php echo $button_pay ?></span>
                  <div class="loader" style="display: none;"></div>
                </button>

            </div>
          </div>
        <?php } ?>
  
<?php } ?>
</div>
  <div id="payment_result" class="col-sm-12">
        
  </div>

  <div class="col-md-12">
    <a href="#" type="button" class="btn-link" data-toggle="modal" data-target="#iuguPaymentModal"><i class="fa fa-plus-circle"></i> <?= $text_add_new ?>
    </a>
  </div>
  
</div>

<div class="addressModal">
    <div class="modal fade" id="iuguPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modal Header</h4>
                </div> -->

                <div class="modal-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <center><h2 class="modal-title"><?= $text_add_new_title ?></h2>  </center>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <div class='card-wrapper' style="margin-bottom: 15px;" ></div>
                    <div class="row">
                        
                      <div class="col-sm-12">


                          <div class="form-horizontal" id="form-credit-card">
                            <form>
                                <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $text_credit_card_label ?></label>
                                <div class="col-sm-9">
                                  <input type="text" name="label" placeholder="<?php echo $placeholder_label ?>" class="form-control" />
                                </div>
                              </div>

                              <!-- Número do Cartão -->
                              <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $text_credit_card_number ?></label>
                                <div class="col-sm-9 card">
                                  <input type="text" name="number" placeholder="<?php echo $placeholder_number ?>" id="card_number" class="form-control" />
                                </div>
                              </div>
                              
                              <!-- Nome do Cliente -->
                              <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $text_credit_card_customer ?></label>
                                <div class="col-sm-9">
                                  <input type="text" name="full_name" placeholder="<?php echo $placeholder_full_name ?>" class="form-control" />
                                </div>
                              </div>
                              
                              <!-- Validate do Cartão -->
                              <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $text_credit_card_validate ?></label>
                                <div class="col-sm-9">
                                  <input type="text" name="credit_card_expiration" placeholder="<?php echo $placeholder_credit_card_expiration ?>" class="form-control" />
                                </div>
                              </div>
                              
                              <!-- Código de verificação -->
                              <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $text_credit_card_cvv ?></label>
                                <div class="col-sm-9">
                                  <input type="number" name="verification_value" placeholder="<?php echo $placeholder_verification_value ?>" class="form-control" />
                                </div>
                              </div>
                              
                              <!-- Parcelamento -->
                              <?php if ($installments !== false): ?>
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $text_installments ?></label>
                                <div class="col-sm-10">
                                  <select name="installments" class="form-control">
                                    <?php foreach($installments as $installment => $value): ?>
                                    <option value="<?php echo $installment ?>"><?php echo sprintf($text_installment, $installment, $value['text_value']) ?></option>
                                    <?php endforeach ?>
                                  </select>
                                </div>
                              </div>
                              <?php endif ?>
                              
                              <!-- Botão de pagamento -->
                              <div class="form-group">
                                <label class="col-sm-6 control-label">
                                  <input type="hidden" name="credit_card_brand" />
                                </label>
                                <div class="col-sm-6">
                                  <button type="button" class="collapsed btn btn-default" data-loading-text="<?php echo $text_loading ?>">
                                    <span class="button-save-text"><?php echo $button_save ?></span>
                                    <div class="loader" style="display: none;"></div>
                                  </button>
                                </div>
                              </div>
                            </form>
                            <!-- CC Lable -->

                            
                          </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.creditCardValidator.js"></script>


<script>
  
   $(function() {
        $('#card_number').validateCreditCard(function(result) {

          console.log("result");
          console.log(result);
          
            if(result.card_type == null)
            {
                $('#card_number').removeClass();
                $('#card_number').addClass("form-control");
            }
            else
            {
                $('#card_number').addClass(result.card_type.name);
            }
        });
    });
  $('input[name="verification_value"]').focus(function(){
    $('.flip-container').toggleClass('flip-container-hover');
  });
  $('input[name="verification_value"]').blur(function(){
    $('.flip-container').toggleClass('flip-container-hover');
  });
  
  $('input[name="number"]').focus(function(){
    $('#credit-card-example-number').stop().animate({
      opacity:1
    }, 1000);
  });
  
  $('input[name="full_name"]').focus(function(){
    $('#credit-card-example-customer').stop().animate({
      opacity:1
    }, 1000);
  });
  
  $('input[name="credit_card_expiration"]').focus(function(){
    $('#credit-card-example-validate').stop().animate({
      opacity:1
    }, 1000);
  });
  
  $('input[name="verification_value"]').focus(function(){
    $('#credit-card-example-ccv').stop().animate({
      opacity:1
    }, 1500);
  });
  
  $('input').blur(function(){
    $('.flip-container .front div:not(#credit-card-example-logo), .flip-container .back div').stop().animate({
      opacity:0
    }, 1000);
  });
  
  var brands_position = {
    mastercard: '3px',
    visa: '-52px',
    amex: '-108px',
    diners: '-165px'
  }
  
  $('input[name="number"]').keyup(function() {

    console.log("number adding");
    if ($(this).val().length <= 6) {
      
      var brand = Iugu.utils.getBrandByCreditCardNumber($(this).val())
      $('input[name="credit_card_brand"]').val(brand);
      
      $('#credit-card-example-logo').stop().animate({
        'background-position-x': 0,
        'background-position-y': brands_position[brand],
        opacity:1
      }, 1500);
    }

    $('#card_number').validateCreditCard(function(result) {

      console.log("result");
      console.log(result);
      
        if(result.card_type == null)
        {
            $('#card_number').removeClass();
            $('#card_number').addClass("form-control");
        }
        else
        {
            $('#card_number').addClass(result.card_type.name);
        }
    });
    
  });
</script>

<script>
Iugu.setAccountID('<?php echo $iugu_account_id ?>');

<?php if ($test_mode): ?>
Iugu.setTestMode(true);
<?php endif ?>

$(document).delegate('.iugu-pay', 'click',function(evt){

  evt.stopImmediatePropagation();
  evt.preventDefault();
  console.log("iugu pay click");
    var dataPaymentMethodId = $(this).attr('data-payment-method-id');    
    
    var text = $('.button-pay-text').html();
    $(this).children(".button-pay-text").html('');
    $(this).children('.loader').show();

    console.log(dataPaymentMethodId);
  $.ajax({
    url: 'index.php?path=payment/iugu_credit_card/pay',
    type: 'post',
    data: {
          payment_method_id: dataPaymentMethodId
        },
    dataType: 'json',
    beforeSend: function() {
      //$(this).button('loading');
    },
    complete: function() {
      //$(this).button('reset');
      $(this).children(".button-pay-text").html(text);
      $(this).children('.loader').hide();
    },      
    success: function(result) { 
      console.log("payment done");
      console.log(result);

      $(this).button('reset');

      if (result.success == true) {
        
        window.location.href = '<?php echo $continue ?>';
      } else {

        $(this).children(".button-pay-text").html(text);
        $(this).children('.loader').hide();

        console.log(result.message);
        console.log("pay fsiled");
        var html = '<div class="alert alert-danger text-center" style="display:none">';
        
        html += '<p style="font-size: 21px;margin: 10px 0;">' + result.message + '</p>';
        html += '</div>';
        
        $('#payment_result').append(html);
        $('#payment_result .alert-danger').slideDown();
      }
    }
  });
});

$('#form-credit-card button').click(function(){
  /* Títular do Cartão */

  var text = $('.button-save-text').html();
  $(this).children(".button-save-text").html('');
  $(this).children('.loader').show();

  var fullname = $('input[name="full_name"]').val().split(' ');
  var firstname = fullname[0];
  fullname.shift();
  var lastname = fullname.join(' ');
  
  /* Número do Cartão */
  var credit_card_number = $('input[name="number"]').val();

  var credit_card_label = $('input[name="label"]').val();
  
  /* Bandeira do Cartão */
  var credit_card_brand = $('input[name="credit_card_brand"]').val();
  
  /* Validade do Cartão */
  var expiration_month = $('input[name="credit_card_expiration"]').val().split('/').shift();
  var expiration_year = $('input[name="credit_card_expiration"]').val().split('/').reverse().shift();
  
  /* Código de Segurançã */
  var cvv = $('input[name="verification_value"]').val();
  
  var error = false;
  
  $('.alert, .text-danger').remove();
  
  /* Verifica se o número do cartão está correto */
  if (Iugu.utils.validateCreditCardNumber(credit_card_number) == false) {
    $('input[name="number"]').after('<span class="text-danger">Cartão inválido!</span>');
    error = true;
  }
  
  /* Verifica validate do código de segurança */
  if (Iugu.utils.validateCVV(cvv, credit_card_brand) == false) {
    $('input[name="verification_value"]').after('<span class="text-danger">Código inválido!</span>');
    error = true;
  }
  
  /* Verifica a validade da data de validade */
  if (Iugu.utils.validateExpiration(expiration_month, expiration_year) == false) {
    $('input[name="verification_value"]').after('<span class="text-danger">Data inválida!</span>');
    error = true;
  }
  
  /* Captura o token e finaliza o pedido */
  if (error == false) {
    var cc = Iugu.CreditCard(credit_card_number, expiration_month, expiration_year, firstname, lastname, cvv);
    
    Iugu.createPaymentToken(cc, function(response) {
      if (response.errors) {

        $(".button-save-text").html(text);
        $('.loader').hide();
        $.map(response.errors, function(error){
          alert(error);
        })
      } else {
        $.ajax({
          url: '<?php echo $link_payment_method_id ?>',
          type: 'POST',
          data: {
            token: response.id,
            label: credit_card_label,
            installment: typeof($('select[name="installments"]').val() != 'undefined') ? $('select[name="installments"]').val() : 1
          },
          dataType: 'json',
          beforeSend: function() {
            //$('#form-credit-card button').button('loading');
          },
          success: function(result) {
            console.log(result);
            console.log("payment_result");

            $(".button-save-text").html(text);
            $('.loader').hide();

            if (result.status == true) {
              console.log("if");
              $("#iugu_payment_methods").append(result.html);
              //location.reload();

            } else {

              console.log(result.message);
              console.log("pay fsiled");
              /*var html = '<div class="alert alert-danger text-center" style="display:none">';
             
              html += '<p style="font-size: 21px;margin: 10px 0;">' + result.message + '</p>';
              html += '</div>';
              
              $('#payment_result').append(html);
              $('#payment_result .alert-danger').slideDown();*/
            }

            $('#iuguPaymentModal').modal('hide');
            $('.close').click();

          },
          complete: function() {
            $(".button-save-text").html(text);
            $('.loader').hide();
            //$('#form-credit-card button').button('reset');
          }
        })
      }
    });
  } else {
    $(".button-save-text").html(text);
    $('.loader').hide();
  }
});

function isArray(what) {
    return Object.prototype.toString.call(what) === '[object Array]';
}
</script>