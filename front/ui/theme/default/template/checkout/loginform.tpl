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

    <button type="submit" class="collapsed btn btn-default"><?= $button_signin ?></button>

    <h5>
        <?= $text_new_customer ?>?
        <a id="sign-up" href="<?= $register ?>" data-replace=""><?= $text_register ?></a>
    </h5>

    <h5>
        <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
    </h5>

    <!-- <button type="submit" class="btn-account btn-large btn-orange tab-btn"></button> -->
</form>
                            