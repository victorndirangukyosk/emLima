<?php echo $header; ?>
<div class="dashboard-wrapper">

  <div class="container">
    
      
    <div class="row">
      
      <div class="col-md-12">
        <div class="my-order-view-dashboard">

          <div id="content" class="">
            <div class="secion-row">
          
          
            <div style="margin: 20px 0px 30px 0px;">
                <a href="<?php echo $back; ?>"> <span class="back-arrow"><i class="fa fa-long-arrow-left"></i> </span> <?= $text_go_back ?></a>
            </div>

            <!-- <div class="title" style="margin-bottom: 16px;"><?php echo $heading_title; ?></div> -->
            <p><?php echo $text_description; ?></p>
            

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                
              <input type="hidden" name="product_id" value="<?= $product_id ?>" />  
                
              <fieldset>
                <legend><?php echo $text_order; ?></legend>
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                  <div class="col-sm-6">
                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control input-lg" />
                    <?php if ($error_firstname) { ?>
                    <div class="text-danger"><?php echo $error_firstname; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                  <div class="col-sm-6">
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control input-lg" />
                    <?php if ($error_lastname) { ?>
                    <div class="text-danger"><?php echo $error_lastname; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-email"><?php echo $entry_email; ?></label>
                  <div class="col-sm-6">
                    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control input-lg" />
                    <?php if ($error_email) { ?>
                    <div class="text-danger"><?php echo $error_email; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                  <div class="col-sm-6">
                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control input-lg" />
                    <?php if ($error_telephone) { ?>
                    <div class="text-danger"><?php echo $error_telephone; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                  <div class="col-sm-6">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                    <input type="text" disabled="" value="<?php echo $order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control input-lg" />
                    <?php if ($error_order_id) { ?>
                    <div class="text-danger"><?php echo $error_order_id; ?></div>
                    <?php } ?>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-date-ordered"><?php echo $entry_date_ordered; ?></label>
                  <div class="col-sm-3">
                     <input type="hidden" name="date_ordered" value="<?php echo $date_ordered; ?>" />
                     <input type="text" disabled="" value="<?php echo $date_ordered; ?>" placeholder="<?php echo $entry_date_ordered; ?>" data-date-format="YYYY-MM-DD" id="input-date-ordered" class="form-control input-lg" />
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_product; ?></legend>
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-product"><?php echo $entry_product; ?></label>
                  <div class="col-sm-6">
                    <input type="hidden" name="product" value="<?php echo $product; ?>"  />              
                    <input type="text" disabled=""  value="<?php echo $product; ?>" class="form-control input-lg" />
                    <?php if ($error_product) { ?>
                    <div class="text-danger"><?php echo $error_product; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-product"><?php echo $entry_unit; ?></label>
                  <div class="col-sm-6">
                    <input type="hidden" name="unit" value="<?php echo $unit; ?>"  />              
                    <input type="text" disabled=""  value="<?php echo $unit; ?>" class="form-control input-lg" />
                    <?php if ($error_unit) { ?>
                    <div class="text-danger"><?php echo $error_unit; ?></div>
                    <?php } ?>
                  </div>
                </div>

                <input type="hidden" name="price" value="<?php echo $price; ?>"  />   
                <!-- product price sending -->
                <div class="form-group required">
                  <label class="col-sm-4 control-label" for="input-model"><?php echo $entry_model; ?></label>
                  <div class="col-sm-6">
                    <input type="hidden" name="model" value="<?php echo $model; ?>" />  
                    <input disabled="" value="<?php echo $model; ?>" class="form-control input-lg" />
                    <?php if ($error_model) { ?>
                    <div class="text-danger"><?php echo $error_model; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                  <div class="col-sm-6">
                    <input type="hidden" name="quantity" value="<?php echo $quantity; ?>" />  
                    
                    <input type="number" step="1" min="1" max="<?php echo $quantity; ?>" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control input-lg" />
                    
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-4 control-label"><?php echo $entry_reason; ?></label>
                  <div class="col-sm-6">
                    <?php foreach ($return_reasons as $return_reason) { ?>
                    <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
                        <?php echo $return_reason['name']; ?></label>
                    </div>
                    <?php } else { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" />
                        <?php echo $return_reason['name']; ?></label>
                    </div>
                    <?php  } ?>
                    <?php  } ?>
                    <?php if ($error_reason) { ?>
                    <div class="text-danger"><?php echo $error_reason; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-4 control-label"><?php echo $entry_opened; ?></label>
                  <div class="col-sm-6">
                    <label class="radio-inline">
                      <?php if ($opened) { ?>
                      <input type="radio" name="opened" value="1" checked="checked" />
                      <?php } else { ?>
                      <input type="radio" name="opened" value="1" />
                      <?php } ?>
                      <?php echo $text_yes; ?></label>
                    <label class="radio-inline">
                      <?php if (!$opened) { ?>
                      <input type="radio" name="opened" value="0" checked="checked" />
                      <?php } else { ?>
                      <input type="radio" name="opened" value="0" />
                      <?php } ?>
                      <?php echo $text_no; ?></label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="input-comment"><?php echo $entry_fault_detail; ?></label>
                  <div class="col-sm-6">
                    <textarea name="comment" rows="10" placeholder="<?php echo $entry_fault_detail; ?>" id="input-comment" class="form-control input-lg"><?php echo $comment; ?></textarea>
                  </div>
                </div>
                <?php if ($site_key) { ?>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-6">
                    <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                    <?php if ($error_captcha) { ?>
                      <div class="text-danger"><?php echo $error_captcha; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
              </fieldset>
              <?php if ($text_agree) { ?>
              <div class="buttons clearfix" style="margin-bottom: 20px;">
                <div class="col-sm-4">
                  
                </div>
                <div class="col-md-6">
                    <?php echo $text_agree; ?>
                  <?php if ($agree) { ?>
                  <input type="checkbox" name="agree" value="1" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="agree" value="1" />
                  <?php } ?>
                  <input type="submit" value="<?php echo $button_submit; ?>" class="btn-orange btn btn-primary" />
                </div>
              </div>
              <?php } else { ?>
              <div class="buttons clearfix" style="margin-bottom: 20px;">
                <div class="col-sm-4">
                  
                </div>
                <div class="col-md-6">
                  <input type="submit" value="<?php echo $button_submit; ?>" class="btn-orange btn btn-primary" />
                </div>
              </div>
              <?php } ?>
            </form>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});

$("#input-quantity").keyup(function () {
  console.log("er");
  ( parseInt($('#input-quantity').val()) > 5 ) ? $('#input-quantity').val('5') : '' ;
});

//--></script>
<?php echo $footer; ?>
