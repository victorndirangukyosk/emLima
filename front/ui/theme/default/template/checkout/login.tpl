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
                            <h3 class="signupheading"><?= $heading_title ?></h3>
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
                                </div>
                            </div>
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
                                            <?php echo $entry_email; ?>
                                        </label>
                                        <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" required autocomplete="email" />
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            <?php echo $entry_password; ?>
                                        </label>
                                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                    </div>

                                    <button type="submit" class="btn-account btn-extra-large btn-orange"><?= $button_signin ?></button>

                                    <h5>
                                        <?= $text_new_customer ?>?
                                        <a id="sign-up" href="<?= $register ?>" data-replace=""><?= $text_register ?></a>
                                    </h5>

                                    <h5>
                                        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
                                    </h5>

                                    <button type="submit" class="btn-account btn-large btn-orange tab-btn"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div><!-- END .checkout -->
    </div><!-- END checkout -->
</div><!-- END .page-container -->

<?= $footer ?>
    
            