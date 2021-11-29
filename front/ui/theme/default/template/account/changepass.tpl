<?php echo $header; ?>

<div role="main" id="main" class="container" style="min-height: 350px;">
    <div class="row mobile-title">
        <div class="col-md-12">
            <div class="content-wrapper with-padding static-page-heading">
                <h2><?= $heading_text ?></h2>                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="content-wrapper with-padding">
            <div class="col-md-12">

                <?php if($error_warning){ ?>
                <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert">&times;</button>
                    <?= $error_warning ?>
                </div>
                <?php } ?>

                <div class="panel panel-default">
                    <div class="panel-heading"><span class="heading-title"><?= $heading_text ?></span></div>
                    <div class="panel-body">
                        <form action="/index.php?path=account/changepass" method="post"  enctype="multipart/form-data" class="form-horizontal">
                            <div class="col-lg-10 col-sm-12 col-xs-6">
                                 <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?= $label_current ?></label>
                                    <div class="col-sm-8">
                                        <input type='password' class="form-control" id="currentpassword" name='currentpassword'>
                                        <?php if ($error_current) { ?>
                                        <div class="text-danger"><?php echo $error_current; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?= $label_new ?></label>
                                    <div class="col-sm-8">
                                        <input type='password' class="form-control" id="newpassword" name='newpassword'>
                                        <?php if ($error_new) { ?>
                                        <div class="text-danger"><?php echo $error_new; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?= $label_retype ?></label>
                                    <div class="col-sm-8">
                                        <input type='password' class="form-control" id="retypepassword" name="retypepassword">
                                        <div id="status"></div> 
                                        <?php if ($error_retype) { ?>
                                        <div class="text-danger"><?php echo $error_retype; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>                     

                                <div class="form-group">
                                    <div class="col-sm-6"></div>
                                    <div class="form-action">
                                        <input type="submit" class="btn btn-lg btn-primary ladda-button" id="Change Password" name="sendmail" value="Send Mail">                                    
                                    </div>
                                </div>

                            </div><!-- END .col-lg-6 -->
                        </form>
                    </div><!-- END .panel-body -->
                </div><!-- END .panel -->
            </div><!-- END .col-md-12 -->
        </div><!-- END .content_wrapper -->
    </div><!-- END .row -->
</div><!-- END #main -->

<?php echo $footer; ?>

