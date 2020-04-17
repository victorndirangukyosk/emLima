<form action="<?php echo $action; ?>" method="post"  autocomplete="off"  enctype="multipart/form-data" id="sign-up-form">
    <div class="row">

        <div id="other_signup_div">
            
            <div class="form-group">
                <label class="col-md-12 control-label sr-only" for="Name"><?= $entry_firstname ?></label>
                <div class="col-md-12">
                    <input id="First Name" name="firstname" type="text" autocomplete="off"  placeholder="<?= $entry_firstname ?>" class="form-control input-md" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12 control-label sr-only" for="Name"><?= $entry_lastname ?></label>
                <div class="col-md-12">
                    <input id="Last Name" name="lastname"  autocomplete="off"  type="text" placeholder="<?= $entry_lastname ?>" class="form-control input-md" required="">
                </div>
            </div>
            
            
            <div class="form-group">
                <label class="col-md-12 control-label sr-only" for="email"><?= $entry_email_address ?></label>
                <div class="col-md-12">
                    <input id="email" name="email"  autocomplete="off"  type="text" placeholder="<?= $entry_email_address ?>" class="form-control input-md" required="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-12 control-label sr-only" for="Phone"><?= $entry_phone ?></label>
                <div class="col-md-12 input-group" style="padding-right: 15px;padding-left: 15px;">

                    <span class="input-group-btn" style="    padding-bottom: 10px;">

                        <p id="button-reward" class="" style="padding: 13px 14px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

                            <font style="vertical-align: inherit;">
                              <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                +<?= $this->config->get('config_telephone_code') ?>                                                
                              </font></font></font>
                            </font>
                        </p>

                    </span>

                    <input id="register_phone_number" autocomplete="off"  name="telephone" type="text" class="form-control input-md" required="" placeholder="<?= $entry_phone?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9">

                    

                </div>
            </div>

            <div style="display:block;"class="form-group required">
                <label class="col-md-12 control-label sr-only" for="company_name">Type</label>
                <div class="col-md-12">
				    <?php foreach($customer_groups as $customer_group){?>
                    <label class="control control--radio" style="display: inline"> 
                        <input type="radio" name="customer_group_id" value="<?=$customer_group['customer_group_id']?>" checked="checked"> <?=$customer_group['name']?> 
                        <div class="control__indicator"></div>
                    </label>
					<?php } ?>

                </div>
            </div>

            <div id='show-me' style='display:none' >
                <div class="form-group">
                    <label class="col-md-12 control-label sr-only" for="company_name"><?= $entry_company ?></label>
                    <div class="col-md-12">
                        <input id="company_name" name="company_name" type="text" placeholder="<?= $entry_company ?>" class="form-control input-md" required="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-12 control-label sr-only" for="Name"><?= $entry_address_1 ?></label>
                    <div class="col-md-12">
                        <input id="company_address" name="company_address" type="text" placeholder="<?= $entry_address_1 ?>" class="form-control input-md" required="">
                    </div>
                </div>
            </div>
            


            <!-- new form end -->
            
        </div>
        
        <input type="hidden" name="fax" value="" id="fax-number" />
        <input type="hidden" name="register_verify_otp" value="" id="register_verify_otp" value="no"/>


        <div class="form-group signup_otp_div" style="display: none" >
            <label class="col-md-12 control-label sr-only" for="otp"><?= $entry_signup_otp ?></label>
            <div class="col-md-12">
                <input id="signup_otp" name="signup_otp" type="text" placeholder="<?= $entry_signup_otp ?>" class="form-control input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="4" maxlength="4">
            </div>
        </div>

        
        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label sr-only" for="submit"><?= $entry_submit ?></label>
            <div class="col-md-12">
                <button id="signup" type="button" class="btn btn-default btn-block btn-lg" style="margin-top:20px;">
                    <span class="signup-modal-text"><?= $heading_text ?></span>
                    <div class="signup-loader" style="display: none;"></div>
                </button>
            </div>
        </div>
    </div>

    <p class="forget-password signup_otp_div" style="display: none">
        <a href="#" id="signup-resend-otp" ><?= $text_resend_otp ?></a>
    </p>
</form>