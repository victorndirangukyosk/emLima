<?php echo $header; ?>

<div class="checkout-wrapper">
    <div class="container" style="    min-height: 300px;">
        <div class="row mobile-title">
            <div class="col-md-12">
                <div class="content-wrapper with-padding static-page-heading">
                    <center><h2><?php echo $heading_title; ?></h2></center>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="content-wrapper with-padding">
                <div class="col-md-12">

                    <?php if($success) { ?>
                      <center> 
                        <p style="color: green"> <?php echo $text_success; ?> </p>
                      </center>
                    <?php } else { ?>
                        <center> 
                          <p> <?php echo $text_error; ?> </p>
                        </center>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-12" style="margin-top: 100px;">
            <center> 
              <a class="btn btn-primary" type="button" data-toggle="modal" data-target="#phoneModal" style="cursor: pointer">
                <i class="fa fa-sign-in"></i> <?php echo $text_login; ?>
              </a>
            </center>
          </div>
        </div>
    </div>
</div>
<?= $login_modal ?>
<?php echo $footer; ?>
</body>

</html>