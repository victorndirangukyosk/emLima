<div class="signupModal-popup">
        <div class="modal fade" id="contactusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <div class="store-find-block">
                        
                        <div class="store-find">
                            <div class="store-head">
                                <h1><?php echo $heading_title; ?></h1>
                                <h4></h4>
                            </div>
                            <div id="contactus-message">
                            </div>
                            <div id="contactus-success-message" style="color: green">
                            </div>
                            <!-- Text input-->
                            <!--<div class="store-form">-->
                                
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="contactus-form">
                            <fieldset>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?php echo $entry_name; ?></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" value="<?php //echo $name; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_name) { ?>
                                        <div class="text-danger"><?php echo $error_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-email"><?php echo $entry_email; ?></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="email" value="<?php // echo $email; ?>" id="input-email" class="form-control" />
                                        <?php if ($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-email">Message</label>
                                    <div class="col-sm-8">
                                        <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"><?php echo $enquiry; ?></textarea>
                                        <?php if ($error_enquiry) { ?>
                                        <div class="text-danger"><?php echo $error_enquiry; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                            </fieldset>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                <div class="col-md-4 pull-right">
                                    <!-- <input id="contactus" class="btn btn-primary" type="button" value="<?= $button_submit ?>" /> -->
                                    <button id="contactus" type="button" name="next" class="btn btn-default btn-block btn-lg">
                                        <span class="contact-modal-text"><?= $button_submit ?></span>
                                        <div class="contact-loader" style="display: none;"></div>
                                    </button>

                                </div>
                            </div>
                        </form>

                            <!-- Text input</div> -->                           
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>