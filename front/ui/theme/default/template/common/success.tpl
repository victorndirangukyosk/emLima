<?php echo $header; ?>

<div class="page-container">
    <div class="session-controller">
        <div class="topspace"></div>
        <div class="containerliquid whitebg" style="min-height: 300px;">
            <div class="container">
                <div class="row">
                    <div class="aligntocenter col-md-12">
                        <h3 class="signupheading"><?php echo $heading_title; ?></h3>
                    </div>

                    <br />
                    <br />
                    <br />
                    <br />
                    
                    <div style="font-size: 16px;">                        
                        <?php echo $text_message; ?>
                    </div>
                    
                    <div class="buttons">
                        <div class="pull-right">
                            <a href="<?php echo $continue; ?>" class="btn btn-primary btn-orange">
                                <?php echo $button_continue; ?>
                            </a>
                        </div>
                    </div>

                    <br />
                    <br />
                    <br />
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $footer; ?>