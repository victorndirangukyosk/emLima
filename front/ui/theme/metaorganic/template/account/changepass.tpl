

   <div class="panel panel-default">
                    <div class="panel-heading"><span class="heading-title"><?= $heading_text ?></span></div>
                    <div class="panel-body">
                        <form action="changepass" method="post"  enctype="multipart/form-data" class="form-horizontal">
                           <!-- <div class="col-lg-10 col-sm-12 col-xs-6">
                                 <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $label_current ?></label>
                                    <div class="col-sm-4">
                                        <input type='password' class="form-control" id="currentpassword" name='currentpassword' required>
                                        <?php if ($error_current) { ?>
                                        <div class="text-danger"><?php echo $error_current; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>-->
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $label_new ?></label>
                                    <div class="col-sm-4">
                                        <input type='password' class="form-control" id="newpassword" name='newpassword' required>
                                        <?php if ($error_new) { ?>
                                        <div class="text-danger"><?php echo $error_new; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $label_retype ?></label>
                                    <div class="col-sm-4">
                                        <input type='password' class="form-control" id="retypepassword" name="retypepassword" required>
                                        <div id="status"></div> 
                                        <?php if ($error_retype) { ?>
                                        <div class="text-danger"><?php echo $error_retype; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>                     

                                <div class="form-group">
                                 
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-2">
                                    <div class="form-action">
                                        <input type="submit" class="btn btn-lg btn-primary ladda-button" id="ChangePassword" name="submit" value="submit">                                    
                                    </div>
                                    </div>
                                </div>

                            </div><!-- END .col-lg-6 -->
                        </form>
                    </div><!-- END .panel-body -->
                </div><!-- END .panel -->



<div style="visibility:hidden; height:100px;" >

<?php echo $header;?>
</div>

