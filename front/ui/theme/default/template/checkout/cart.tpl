<?php echo $header; ?>

<div class="page-container">
    <div class="checkout">
        <div class="checkout">
            
            <?php if ($attention) { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>

            <div id="step-container" class="container-fluid">
                <div class="wizard">
                    <div class="row">
                        <div class="col-sm-4 col-xs-4">
                            <div id="go-back" class="wizardno activewizard">
                                <a>
                                    <i class="fa fa-check"></i>
                                    <dummy>1</dummy>
                                </a>
                                <p><?= $text_cart ?></p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="wizardno">
                                <a>
                                    <dummy>2</dummy>
                                    <i class="fa fa-check"></i>
                                </a>
                                <p><?= $text_signin ?></p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="wizardno">
                                <a>
                                    <dummy>3</dummy>
                                    <i class="fa fa-check"></i>
                                </a>
                                <p><?= $text_place_order ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="checkout-page-container">
                <div class="containerliquid whitebg">
                    
                    <?php if ($products) { ?>   
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 reviewheading">
                                <div class="leftnav">
                                    <p><?= $text_more ?></p>
                                    <span>&lt;</span>
                                    <a href="<?= $home ?>" class="back"><?= $keep_shopping ?></a>
                                </div>
                                <h3><?= $heading_text ?></h3>
                            </div>
                            <div class="divideline h-f-t pdg90" style="display: none;"></div>
                            <div class="col-md-12 table">
                                
                                <form id="cart_form" action="<?= $action ?>" method="post">
                                
                                <table id="normal-items" class="checkouttable checkouttable-cart">
                                    <thead>
                                        <tr>
                                            <th class="col6head"></th>
                                            <th class="col1head"><?= $column_items ?></th>
                                            <th class="col1head"><?= $column_unit ?></th>
                                            <th class="col2head"><?= $column_price ?></th>
                                            <th class="col3head"><?= $column_quantity ?></th>
                                            <th class="col4head"><?= $column_subtotal ?></th>
                                            <th class="col5head"></th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                         
                                        <?php foreach ($products as $product) { ?>    
                                        <tr id="row_<?= $product['product_store_id'] ?>">
                                            <td class="itemimg">
                                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
                                            </td>
                                            <td class="h-f-t itemsdesc">
                                                <div class="itemname">
                                                    <?php echo $product['name']; ?>
                                                    <?php if (!$product['stock']) { ?>
                                                    <span style="color: red;" class="text-danger">***</span>
                                                    <?php } ?>
                                                    <?php if ($product['option']) { ?>
                                                    <?php foreach ($product['option'] as $option) { ?>
                                                    <br />
                                                    <span><?php echo $option['name']; ?>: <?php echo $option['value']; ?></span>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <?php if ($product['reward']) { ?>
                                                    <br />
                                                    <span><?php echo $product['reward']; ?></span>
                                                    <?php } ?>
                                                    <?php if ($product['recurring']) { ?>
                                                    <br />
                                                    <span class="label label-info"><?php echo $text_recurring_item; ?></span> 
                                                    <span><?php echo $product['recurring']; ?></span>
                                                    <?php } ?>
                                                    
                                                    <div class="unitprice_tabmob">
                                                        <?= $product['price'] ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="h-f-t unitprice">
                                                <?= $product['unit'] ?>
                                            </td>
                                            <td class="h-f-t unitprice">
                                                <?= $product['price'] ?>
                                            </td>
                                            <td class="h-f-t quantity-td">
                                                <div class="quantity-controller">
                                                    <div class="minus" data-id="<?= $product['product_store_id'] ?>"></div>
                                                    <div class="num"><?= $product['quantity'] ?></div>
                                                    <div class="plus" data-id="<?= $product['product_store_id'] ?>"></div>
                                                </div>
                                            </td>
                                            <td class="h-f-t unitprice">
                                                <span class="bold">
                                                <?= $product['total'] ?>
                                                </span>
                                            </td>
                                            <td class="h-f-t">
                                                <a onclick="checkout_cart.remove('<?php echo $product['key']; ?>');" class="deleteitem"></a>
                                            </td>
                                            <td class="mob-td v-f-t">
                                                <div class="mob-item-name">
                                                     <?php echo $product['name']; ?>
                                                    <?php if (!$product['stock']) { ?>
                                                    <span class="text-danger">***</span>
                                                    <?php } ?>
                                                    <?php if ($product['option']) { ?>
                                                    <?php foreach ($product['option'] as $option) { ?>
                                                    <br />
                                                    <span><?php echo $option['name']; ?>: <?php echo $option['value']; ?></span>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <?php if ($product['reward']) { ?>
                                                    <br />
                                                    <span><?php echo $product['reward']; ?></span>
                                                    <?php } ?>
                                                    <?php if ($product['recurring']) { ?>
                                                    <br />
                                                    <span class="label label-info"><?php echo $text_recurring_item; ?></span> 
                                                    <span><?php echo $product['recurring']; ?></span>
                                                    <?php } ?>
                                                    
                                                </div>
                                                <span class="mob-price">
                                                    <span class="bold">
                                                        <?= $product['total'] ?>
                                                    </span>
                                                    <span class="each-item">
                                                        (
                                                        <?= $product['price'] ?>
                                                        each
                                                        )
                                                    </span>
                                                </span>
                                                <br>
                                                <div class="item-controller">
                                                    <div class="quantity-controller">
                                                        <div class="minus" data-id="<?= $product['product_store_id'] ?>"></div>
                                                        <div class="num"><?= $product['quantity'] ?></div>
                                                        <div class="plus" data-id="<?= $product['product_store_id'] ?>"></div>
                                                    </div>
                                                </div>
                                                <div class="remove">
                                                    <input type="text" name="quantity[<?= $product['key'] ?>]" value="<?= $product['quantity'] ?>"  />
                                                    <a onclick="checkout_cart.remove('<?php echo $product['key']; ?>');" class="deleteitem"></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>     
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div id="empty-container" class="container-fluid">
                        <div class="row">
                            <div class="pdg150 placeordersection">
                                <h3><?= $text_empty ?></h3>
                                <a href="<?= $home ?>" class="back btn btn-large-2 btn-orange"><?= $button_continue ?>s</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if($totals){ ?>
                    <div class="cart-total table">
                        <div class="divideline less-margin-top"></div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="coupon-col">
                                        <div class="offer-coupon-q">
                                            <h4><?= $text_coupon ?></h4>
                                        </div>
                                        <div class="discount-coupon-form">
                                            
                                            <?php echo $coupon; ?>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- start reward form -->
                            <div class="divideline light"></div>
                            <div class="container-fluid">                        
                                <div class="row">
                                    <div class="col-md-12">
                                        
                                        <h5 id="reward-error" style="display: none;"></h5>                                        
                                        <h5 id="reward-success" style="display: none;"></h5>
                                                
                                        <div class="coupon-col">
                                            <div class="offer-coupon-q">
                                                <h4><?= $text_apply_reward_points ?></h4>
                                            </div>
                                            <div class="discount-coupon-form">
                                                <form id="coupon-form" action="">
                                                    <input type="text" name="reward" placeholder="<?= $entry_reward_points ?>" maxlength="10" />
                                                    <button id="button-reward" class="ladda-button" type="button" data-style="zoom-out">
                                                        <?= $button_add ?>
                                                    </button>
                                                </form>                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- END reward form --> 
                        <div class="divideline light"></div>
                        <div class="container-fluid">
                            <div class="row-check-out">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info">
                                            <div class="title"><?= $title_availability ?></div>
                                            <div class="message"><?= $title_text1 ?></div>
                                        </div>
                                        <div class="info">
                                            <div class="title"><?= $title_price ?></div>
                                            <div class="message">
                                                <?= $title_text2 ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-md-offset-1">
                                    <table class="cart-info-table">
                                        <tbody>
                                            <?php foreach ($totals as $total) { ?>
                                            <tr>
                                                <td class="big"><?php echo $total['title']; ?>:</td>
                                                <td><?php echo $total['text']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <!-- <a href="#modal_tax_location" data-toggle="modal"><?= $tax_city ?></a> -->
                                    <p class="tax_location">
                                        
                                        <?= $text_restriction1 ?> <a href="#" data-toggle="modal"><?= $tax_city ?></a> <?= $text_restriction2 ?> 
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <?php if ($dont_allow): ?>
                                        
                                    <button  disabled="disabled" class="btn-account-checkout btn-large btn-orange"><?= $button_place_order ?></button>
                                    <?php else: ?>
                                    <?php if($is_login){ ?>
                                    <a href="<?= $this->url->link('checkout/checkout') ?>" class="btn-account-checkout btn-large btn-orange"><?= $button_place_order ?></a>
                                    <?php }else{ ?>
                                    <a href="<?= $this->url->link('checkout/login') ?>" class="btn-account-checkout btn-large btn-orange"><?= $button_place_order ?></a>
                                    <?php } ?>
                                     <?php endif ?>
                                    

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_tax_location" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div style="padding: 0 0 10px" class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change city</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label>City</label>
              <select name="city_id" class="form-control" id="city_id">
                  <?php foreach($cities as $city){ ?>
                  <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                  <?php } ?>
              </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn-tax-location btn btn-primary">Submit</button>
      </div>
    </div>

  </div>
</div>

<?php echo $footer; ?>

<script>
    $(function(){
        $('.btn-tax-location').click(function(){
            $.post('index.php?path=checkout/cart/tax_location', { city_id : $('#city_id').val() }, function(){
                location = location;
            });
        });
    });
</script>
<script type="text/javascript">
    $('#button-reward').on('click', function() {
        $.ajax({
            url: 'index.php?path=checkout/reward/reward',
            type: 'post',
            data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#button-reward').button('loading');
                $('#reward-error').hide();
                $('#reward-success').hide();
            },
            complete: function() {
                $('#button-reward').button('reset');
            },
            success: function(json) {
                if (json['error']) {
                    $('#reward-error').html('<p id="error">'+json['error']+'</p>').show();
                }else{
                    $('#reward-success').html('<p id="success">'+json['success']+'</p>').show();
                    loadTotals($('input#shipping_city_id').val());
                }
            }
        });
    });

    function loadTotals($city_id) {
    $.ajax({
        url: 'index.php?path=checkout/totals&city_id=' + $city_id,
        type: 'post',
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#checkout-total-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#checkout-total-wrapper').html(html);
            window.location.reload(false); 
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
</script>