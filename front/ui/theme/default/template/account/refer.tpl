<?php echo $header; ?>

<div role="main" id="main" class="container" style="min-height: 350px;">
    <div class="row mobile-title">
        <div class="col-md-12">
            <div class="content-wrapper with-padding static-page-heading">
                <h2><?= $heading_title ?></h2>                
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
                        <form action="<?= $refer ?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
                            <div class="col-lg-10 col-sm-12 col-xs-6">
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?= $entry_refral?></label>
                                    <div class="col-sm-8">
                                        <input type='text' class="form-control" id="yourname" name ='yourname' value="<?= $yourname; ?>">
                                        <?php if ($error_yourname) { ?>
                                        <div class="text-danger"><?php echo $error_yourname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?= $entry_refered; ?></label>
                                    <div class="col-sm-8">
                                        <input type='text' class="form-control" id="email" name="email" value='<?= $email ?>'>
                                        <div id="status"></div> 
                                        <?php if ($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-4 control-label" for="input-name"><?php echo $entry_message; ?></label>
                                    <div class="col-sm-8">
                                        <textarea id="message" name="message" class="form-control" cols="50" rows="6"><?= $entry_message ?></textarea>
                                        <?php if ($error_message) { ?>
                                        <div class="text-danger"><?php echo $error_message; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>                     

                                <div class="form-group">
                                    <div class="col-sm-6"></div>
                                    <div class="form-action">
                                        <input type="submit" class="btn btn-lg btn-primary ladda-button" id="sendmail" name="sendmail" value="Send Mail">                                    
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

<script>
  /*  $('#sendmail').bind('click', function (e) {
        
        $.ajax({
            type:'POST',
            url: '',
            data: $('.form-horizontal').serialize(),
            dataType: 'json',
            success: function (json) {
               alert("success");
            }
        });
        
        e.preventDefault();
    });
  */  
</script>