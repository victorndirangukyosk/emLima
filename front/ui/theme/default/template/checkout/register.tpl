<?php echo $header; ?>

<div class="page-container">
    <div class="checkout">
        <div class="checkout">

            <div id="step-container" class="container-fluid">
                <div class="wizard">
                    <div class="row">
                        <div class="col-sm-4 col-xs-4">
                            <div id="go-back" class="wizardno checkicon">
                                <a>
                                    <i class="fa fa-check"></i>
                                    <dummy>1</dummy>
                                </a>
                                <p><?= $text_cart ?></p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="wizardno activewizard">
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

            <div class="containerliquid whitebg">
                
            <div class="container">
                <div class="row">
                    <div class="aligntocenter col-md-12">
                        <h3 class="signupheading"><?= $heading_text ?></h3>
                    </div>
                    
                    <div class="col-md-12 session-notice" style="display: none;"></div>
                    
                    <div class="col-md-5">
                        <div class="socialsignin-container">
                            <div class="socialsignin">
                               <div id="facebook-container" style="display: block;">
                                   <a href="<?= $facebook ?>" class="facebooksignin"><span>
                                            <i class="fa fa-facebook"></i>
                                        </span>
                                        <?= $button_facebook ?>
                                    </a>
                                </div>
                                <div id="google-container" style="display: block;">
                                    <a href="<?= $this->url->link('account/google') ?>" class="googlesignin"><span>
                                            <i class="fa fa-google-plus"></i>
                                        </span>
                                        <?= $button_google ?>
                                    </a>
                                </div>
                            </div></div>
                    </div>
                    <div class="col-md-2">
                        <div class="ordiv">
                            <div class="linetobottom"></div>
                            <span>or</span>
                            <div class="linetobottom"></div>
                        </div>
                    </div>
                    <div class="col-md-5 session-page-container">
                        <div class="signupform">
                            
                            <?php if ($error_warning) { ?>
                                <span class="error"><?php echo $error_warning; ?></span><br /><br />
                            <?php } ?>
                            
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="sign-up-form" novalidate="novalidate" class="bv-form">
                                <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                                <div class="form-group">
                                    <label>
                                        <?php echo $entry_firstname; ?>
                                    </label>
                                    <input type="text" placeholder="Enter your first name" name="firstname" class="form-control" value="<?php echo $firstname; ?>" />
                                    <?php if ($error_firstname) { ?>
                                      <small class="help-block" ><?php echo $error_firstname; ?></small>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <?php echo $entry_lastname; ?>
                                    </label>
                                    <input type="text" placeholder="Enter your last name" name="lastname" class="form-control" value="<?php echo $lastname; ?>" />
                                    <?php if ($error_lastname) { ?>
                                      <small class="help-block" ><?php echo $error_lastname; ?></small>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <?php echo $entry_email; ?>
                                    </label>
                                    <input type="text" placeholder="Enter your email address" name="email" class="form-control" value="<?php echo $email; ?>" />
                                    <?php if ($error_email) { ?>
                                      <small class="help-block" ><?php echo $error_email; ?></small>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <?php echo $entry_telephone; ?>
                                    </label>
                                    <input type="text" placeholder="Enter your phone no" name="telephone" class="form-control" value="<?php echo $telephone; ?>" />
                                    <?php if ($error_telephone) { ?>
                                      <small class="help-block" ><?php echo $error_telephone; ?></small>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <?php echo $entry_fax; ?>
                                    </label>
                                    <input type="text" placeholder="Enter your fax no" name="fax" class="form-control" value="<?php echo $fax; ?>" />
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <?php echo $entry_password; ?>
                                    </label>
                                    <input type="password" placeholder="Enter your password" name="password" class="form-control" value="<?php echo $password; ?>" />
                                    <?php if ($error_password) { ?>
                                      <small class="help-block" ><?php echo $error_password; ?></small>
                                    <?php } ?>
                                </div>
            
                                <button type="submit" class="btn-account btn-orange signup-font-fix"><?= $button_create ?></button>
                                
                                <h5>
                                    <?= $text_already ?>
                                    <a data-replace="" href="/index.php?path=checkout/login" id="sign-in"><?= $text_signin ?></a>
                                </h5>
                                <!-- <button type="submit" class="btn-account btn-orange tab-btn">Create an Account</button> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>
  