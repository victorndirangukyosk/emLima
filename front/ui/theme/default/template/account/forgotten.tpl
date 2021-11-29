<?php echo $header; ?>

<div class="page-container">
    <div class="session-controller">
        <div class="topspace"></div>
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
                                    <a href="<?= $facebook ?>"class="facebooksignin">
                                        <span>
                                            <i class="fa fa-facebook"></i>
                                        </span>
                                        <?= $button_facebook ?>
                                    </a>
                                </div>
                                <div id="google-container" style="display: block;">
                                    <a href="<?= $this->url->link('account/google') ?>" class="googlesignin">
                                        <span>
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
                                <span class="error"><?php echo $error_warning; ?></span>
                            <?php } ?>
                                                        
                            <span style="display: none;" class="success"></span>
                            
                            <form id="forgot-password-form" novalidate="novalidate" class="bv-form" method="post" action="<?= $action ?>">
                                <button type="submit" class="bv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
                                <div class="form-group">
                                    <label>
                                        <?= $label_email ?>
                                    </label>
                                    <input type="email" data-bv-emailaddress-message="Invalid email" data-bv-emailaddress="true" data-bv-notempty-message="Email required" data-bv-notempty="true" placeholder="Enter your email address" name="email" id="email" class="form-control" data-bv-field="email">
                                    <small style="display: none;" class="help-block" data-bv-validator="emailAddress" data-bv-for="email" data-bv-result="NOT_VALIDATED"><?= $error_invalid ?></small><small style="display: none;" class="help-block" data-bv-validator="notEmpty" data-bv-for="email" data-bv-result="NOT_VALIDATED"><?= $error_req ?></small></div>
                                <p class="forgottext"><?= $text_para ?></p>
                                <button type="submit" class="btn-orange btn-submit"><?= $button_submit ?></button>
                                <a data-replace="" href="<?= $this->url->link('account/register') ?>" id="cancel" class="btn-cancel btn-white"><?= $button_cancel ?></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>