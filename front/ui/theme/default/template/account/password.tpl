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
                    <div class="col-md-12 session-page-container">
                        <div class="signupform">
                            <span style="display: none;" class="error"></span>
                            <span style="display: none;" class="success"></span>
                            <form method="post" action="<?= $action ?>" class="bv-form">
                                <div class="form-group">
                                    <label><?= $label_password ?>
                                    </label>                                    
                                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                    <?php if ($error_password) { ?>
                                    <small class="help-block"><?php echo $error_password; ?></small>
                                    <?php } ?>                                    
                                </div>
                                <div class="form-group">
                                    <label><?= $label_confirm_password ?>
                                    </label>                                    
                                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
                                    <?php if ($error_confirm) { ?>
                                    <div class="text-danger"><?php echo $error_confirm; ?></div>
                                    <?php } ?>
                                </div>
                                
                                <button type="submit" class="btn-orange btn-submit"><?= $button_submit ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?> 