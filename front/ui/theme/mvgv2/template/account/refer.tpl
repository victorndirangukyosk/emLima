<?php echo $header; ?>

<div role="main" id="main" class="container" style="min-height: 350px;">
                        <div class="col-md-9 nopl">
                            <div class="dashboard-cash-content">
                                 
                                <div class="row">
                                     <div class="col-md-12">
                                            <div class="cash-block">
                                                <span class="your-cash"> <?= $total_signup ?>  </span>
                                                <span class="your-cash" style="color: green;"> <?= $total_signup_amount ?>  </span>
                                            </div>
                                            <div class="cash-block">
                                                <span class="your-cash"> <?= $total_referral_bonus ?>  </span>
                                                <span class="your-cash" style="color: green;"> <?= $total_referral_bonus_amount ?>  </span>
                                            </div>
                                     </div>
                                </div>

                                <div class="social-login-section">

                                    <?php $link = $_SERVER['REQUEST_URI'];?>

                                    <div class="row">
                                            <div class="col-sm-3">
                                                <a class="social-login-btn facebook-btn" href="https://www.facebook.com/sharer/sharer.php?app_id=<?php echo $app_id ?>&u=<?= $redirect_url ?>&sdk=joey&display=popup&ref=plugin&src=share_button" onclick="return !window.open(this.href, 'Facebook', 'width=640,height=580')"> <?= $text_share ?> on fb</a>
                                            </div>

                                            <div class="col-sm-3">

                                                <a href="https://plus.google.com/share?url=<?= $refer_link ?>" class="social-login-btn googleplus-btn" onclick="return !window.open(this.href, 'Google', 'width=640,height=580')" > <?= $text_share ?> on G+</a>

                                            </div>

                                            <div class="col-sm-2">
                                                <a class="twitter-share-button"  href="https://twitter.com/intent/tweet?text=<?= $refer_text ?>&url='<link rel='canonical'
                                        href='<?= $refer_link ?>'>">Tweet</a>

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

                                                            <input type='hidden' class="form-control" id="yourname" name ='yourname' value="<?= $yourname; ?>">

                                                            <div class="form-group required">
                                                                <label class="col-sm-4 control-label" for="input-link"><?= $entry_refral_link?></label>
                                                                <div class="col-sm-8">
                                                                    <input type='text' class="form-control" id="refer_link" name='refer_link' value="<?= $refer_link; ?>" disabled/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group required">
                                                                <label class="col-sm-4 control-label" for="input-name"><?= $entry_refral?></label>
                                                                <div class="col-sm-8">
                                                                    <input type='text' class="form-control" id="yourname" name ='yourname' value="<?= $yourname; ?>" disabled>
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
                                                            <!-- <div class="form-group required">
                                                                <label class="col-sm-4 control-label" for="input-name"><?php echo $entry_message; ?></label>
                                                                <div class="col-sm-8">
                                                                    <textarea id="message" name="message" class="form-control" cols="50" rows="6"></textarea>
                                                                    <?php if ($error_message) { ?>
                                                                    <div class="text-danger"><?php echo $error_message; ?></div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>     -->                 

                                                            <div >
                                                                <label class="col-sm-4 control-label" for="input-name"></label>
                                                                
                                                                <div class="col-sm-8" style="color: green">
                                                                    
                                                                    <?php if($referral_data['referrer']) { ?>
                                                                        <center>*<?=$referral_data['referrer'] ?></center>
                                                                    <?php } ?>

                                                                    <?php if($referral_data['referred']) { ?>
                                                                        <center>*<?=$referral_data['referred'] ?></center>
                                                                    <?php } ?> 
                                                                    
                                                                </div>
                                                            </div>
                                                            

                                                            
                                                            <div class="form-group">
                                                                <div class="col-sm-6"></div>
                                                                <div class="form-action">
                                                                    <input type="submit" class="btn btn-lg btn-primary ladda-button" id="sendmail" name="sendmail" value="<?= $entry_send_mail ?>">                                    
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $footer; ?>

<script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>
<script >
  window.___gcfg = {
    lang: 'zh-CN',
    parsetags: 'onload'
  };
</script>
<script src="https://apis.google.com/js/client:platform.js" async defer></script>
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
  $(document).delegate('#facebook-wall-post', 'click', function(e) {
        console.log("facebook-btn click");
        $.ajax({
            url: 'index.php?path=common/home/getFacebookWallPostRedirectUrl&redirect_url=<?= $_SERVER["REQUEST_URI"]?>',
            type: 'post',
            dataType: 'json',
            success: function(json) {
                console.log(json);
                e.preventDefault();
                location.href = json['facebook'];
            }
        });
    });
</script>