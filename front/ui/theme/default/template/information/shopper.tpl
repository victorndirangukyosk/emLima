
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

                <div class="panel panel-default">
                    <div class="panel-heading"><span class="heading-title"><?= $text_heading ?></span></div>
                    <div class="panel-body">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="col-lg-6 col-sm-12 col-xs-6">
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_firstname ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_firstname) { ?>
                                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_lastname ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_lastname) { ?>
                                        <div class="text-danger"><?php echo $error_lastname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_username ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="username" value="<?php echo $username; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_username) { ?>
                                        <div class="text-danger"><?php echo $error_username; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_password ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="password" value="<?php echo $password; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_password) { ?>
                                        <div class="text-danger"><?php echo $error_password; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class='form-group required'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_mobile ?></label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="mobile" value="<?php echo $mobile; ?>" />
                                        <?php if ($error_mobile) { ?>
                                        <div class="text-danger"><?php echo $error_mobile; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_telephone ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="telephone" value="<?php echo $telephone; ?>" />
                                    </div>
                                </div>                                                


                            </div>
                            <div class="col-lg-6 col-sm-12 col-xs-6">

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_email ?></label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="email" value="<?php echo $email; ?>" />
                                        <?php if ($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>							

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_city ?></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="city_id" >
                                        <?php foreach($cities as $city) { ?>
                                        <?php if ($city['city_id'] == $city_id) { ?>
                                        <option selected value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                                        <?php }else{ ?>
                                        <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>							

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $text_address ?></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="address"><?= $address ?></textarea>
                                        <?php if ($error_address) { ?>
                                        <div class="text-danger"><?php echo $error_address; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>							
                                
                                <div class="form-group">
                                    <div class="col-sm-3"></div>
                                    <div class="form-action">
                                        <input type="submit" value="Submit" class="btn btn-primary" />
                                    </div>
                                </div>

                            </div><!-- END .col-lg-6 -->
                    </div><!-- END .panel-body -->
                </div><!-- END .panel -->
            </div><!-- END .col-md-12 -->
        </div><!-- END .content_wrapper -->
    </div><!-- END .row -->
</div><!-- END #main -->

<?= $footer ?>