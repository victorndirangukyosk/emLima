<div class="phoneModal-popup">
        <div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h1><?= $text_number_verification ?></h1>
                                        <h4><?= $text_enter_number_to_login ?></h4>
                                    </div>
                                    <div id="login-message">
                                    
                                    </div>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="login-form" autocomplete="off" method="post" enctype="multipart/form-data" novalidate>
                                            
                                            <div class="row">
                                                <div class="form-group">
                                                    <!--<label class="col-md-12 control-label sr-only" for="Phone"><?= $text_enter_phone?></label>
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
                                                        <input id="phone_number" autocomplete="false" name="phone" type="text" class="form-control input-md" required="" placeholder="<?= $text_enter_phone?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" onclick="phoneClicked()" >

                                                        

                                                    </div>-->

                                                    <div class="col-md-12" style="padding-right: 15px;padding-left: 15px;">

                                                        <!--<p class="seperator" style="margin-top:  13px;"><?= $text_or ?></p>-->
                                                            
                                                        <input id="email" autocomplete="off" name="email" type="email" class="form-control input-md"  placeholder="<?= $text_enter_email_address?>" onclick="emailClicked()">

                                                    </div>
													<div class="col-md-12" style="padding-right: 15px;padding-left: 15px;">

                                                        <input id="password" autocomplete="off" name="password" type="password" class="form-control input-md"  placeholder="password" onclick="emailClicked()">

                                                    </div>

                                                </div>

                                                <!-- Button -->
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label sr-only" for="next"><?= $text_move_next?></label>
                                                    <div class="col-md-12">
                                                        <button id="login_send_otp" type="button" name="next" class="btn btn-default btn-block btn-lg">
                                                            <span class="login-modal-text"><?= $text_login ?></span>
                                                            <div class="login-loader" style="display: none;"></div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                          
                                        </form>
                                        <p class="seperator" style="margin-top:  13px; margin-bottom:10px"><?= $text_or ?></p>
                                        <div class="social-login-section">

                                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#signupModal-popup" class="social-login-btn facebook-btn" style="border-radius: 0px;">

                                            
                                            <span class="signup-modal-text" style="font-size: 18px"><?= $text_signup?></span>
                                                
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="code-varifications">
                                    <div class="store-find">

                                        
                                        <div class="store-head">
                                            <!-- <button name="prev" class="btn-link">Back</button> -->
                                            <h1><?= $text_code_verification?></h1>
                                            <!-- <h4><?= $text_enter_code_in_area?></h4> -->
                                            <h4 id="otp-message"></h4>
                                        </div>
                                        <div class="store-form">
                                            <form id="login-otp-form" action="" method="post" enctype="multipart/form-data" novalidate>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label sr-only" for="Phone"><?= $text_enter_phone ?></label>
                                                        <div class="col-md-12 ">
                                                            <input id="verify_otp" name="verify_otp" type="text" placeholder="" class="form-control input-md" required="" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="4" maxlength="4">
                                                        </div>

                                                    </div>

                                                    <input id="customer_id" name="customer_id" type="hidden" value="">

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label sr-only" for="next"><?= $text_move_next?></label>
                                                        <div class="col-md-12">
                                                            <!--  <button id="login_verify_otp" name="login_verify_otp" class="btn btn-default btn-block btn-lg"><?= $text_verify?></button> -->

                                                            <button id="login_verify_otp" type="button" name="login_verify_otp" class="btn btn-default btn-block btn-lg">
                                                                <span class="login-otp-modal-text"><?= $text_verify ?></span>
                                                                <div class="login-otp-loader" style="display: none;"></div>
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>

                                                <p class="forget-password">
                                                    <a href="#" id="login_resend_otp" ><?= $text_resend_otp ?></a>
                                                </p>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="success-message">
                                    <div class="store-find">
                                        <div class="store-form">
                                            <div class="success-sign"><i class="fa fa-check-circle"></i></div>
                                            <h1><?= $text_success_verification?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="store-footer text-center">
                                <p><?= $text_enter_you_agree?> 

                                <a target="_blank" alt="<?= $text_terms_of_service ?>" href="<?= $account_terms_link ?>">

                                <strong><?= $text_terms_of_service ?></strong>
                                </a>
                                 &amp; 
                                 <a target="_blank" alt="<?= $text_privacy_policy?>" href="<?= $privacy_link ?>">
                                 <strong><?= $text_privacy_policy?></strong>
                                 </a>
                                 </p>
                            </div>
                            <!-- next div code -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        
        
        
        function emailClicked() {
            $('#phone_number').val('');   
            //$('#email').removeAttr('disabled');     
        }

        function phoneClicked() {
            $('#email').val('');
            //$('#phone_number').removeAttr('disabled'); 
        }

    </script>