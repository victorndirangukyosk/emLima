
<?php echo $header; ?>
<div class="hero-seller-image">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="store-find-block">
                        <div class="store-logo"><h1><?= $text_seller_signup?></h1></div>
                        <div class="store-find">
                            <div class="store-head">
                                <h4><?= $text_fill_details ?></h4>
                                  <?php if($error_warning){ ?>
                                                    <div class="alert alert-danger">
                                                        <button class="close" data-dismiss="alert">&times;</button>
                                                        <?= $error_warning ?>
                                                    </div>
                                                    <?php } ?>
                            </div>
                            <!-- Text input-->
                            <div class="store-form">
                                <div class="row">
                 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">

                                   <div class="col-lg-6 col-sm-12 col-xs-6">
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_firstname ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_firstname) { ?>
                                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_lastname ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_lastname) { ?>
                                        <div class="text-danger"><?php echo $error_lastname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                
                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_email ?></label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="email" id="email" name="email" value="<?php echo $email; ?>" />
                                        <?php if ($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>                          

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_password ?></label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" value="<?php echo $password; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_password) { ?>
                                        <div class="text-danger"><?php echo $error_password; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_confirm_password ?></label>
                                    <div class="col-sm-9">
                                        <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_confirm_password) { ?>
                                        <div class="text-danger"><?php echo $error_confirm_password; ?></div>
                                        <?php } ?>
                                        <?php if ($error_mismatch_password) { ?>
                                        <div class="text-danger"><?php echo $error_mismatch_password; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                               
                                <div class='form-group'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_tin_no ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="tin_no" id="tin" value="<?php echo $tin_no; ?>" />
                                    </div>
                                </div>

                                <div class='form-group required'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_mobile ?></label>
                                    <div class="col-sm-9 input-group" style="margin-left: -15px;" >

                                        <span class="input-group-btn" style="    padding-bottom: 10px;">

                                            <p id="button-reward" class="" style="padding: 13px 14px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

                                                <font style="vertical-align: inherit;">
                                                  <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                    +<?= $this->config->get('config_telephone_code') ?>                                                
                                                  </font></font></font>
                                                </font>
                                            </p>

                                        </span>

                                        <input class="form-control" type="text" id="mobile" name="mobile" value="<?php echo $mobile; ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" />
                                        <?php if ($error_mobile) { ?>
                                        <div class="text-danger"><?php echo $error_mobile; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_telephone ?></label>
                                    <div class="col-sm-9 input-group" style="margin-left: -15px;">

                                        <span class="input-group-btn" style="    padding-bottom: 10px;">

                                            <p id="button-reward" class="" style="padding: 13px 14px;border-radius: 2px;font-size: 15px;font-weight: 600;color: #fff;background-color: #522e5b;border-color: #522e5b;display: inline-block;margin-bottom: 0;font-size: 14px;line-height: 1.42857143;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;margin-right: -1px;">

                                                <font style="vertical-align: inherit;">
                                                  <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                    +<?= $this->config->get('config_telephone_code') ?>                                                
                                                  </font></font></font>
                                                </font>
                                            </p>

                                        </span>

                                        <input type="text" class="form-control" name="telephone" value="<?php echo $telephone; ?>"  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9"/>
                                    </div>
                                </div>                                                


                            </div>
                            <div class="col-lg-6 col-sm-12 col-xs-6">                                           

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_store ?></label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="store_name" value="<?php echo $store_name; ?>" />
                                        <?php if ($error_store_name) { ?>
                                        <div class="text-danger"><?php echo $error_store_name; ?></div>
                                        <?php } ?>
                                    </div>                                    
                                </div> 

                                  <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_address ?></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="address"><?= $address ?></textarea>
                                        <?php if ($error_address) { ?>
                                        <div class="text-danger"><?php echo $error_address; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>  

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_city ?></label>
                                    <div class="col-sm-9">
                                        
                                        <select name="city_id" class="form-control">
                                        <?php foreach($cities as $city){ ?>
                                        <?php if($city['city_id'] == $city_id) { ?>
                                        <option selected value='<?= $city["city_id"] ?>'><?= $city['name'] ?></option>
                                        <?php } else { ?>
                                        <option value='<?= $city["city_id"] ?>'><?= $city['name'] ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                        </select>
                                        
                                    </div>
                                </div>   

                                 <div class='form-group'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_business ?></label>
                                    <div class="col-sm-9">
                                        <select name="business[]" multiple="" class="form-control">
                                            <?php foreach($categories as $category){ ?>
                                            <?php if(in_array($category['name'], $business)){ ?>
                                            <option selected=""><?= $category['name'] ?></option>
                                            <?php }else{ ?>
                                            <option><?= $category['name'] ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block">
                                            <?= $text_warning ?>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_type ?></label>
                                    <div class="col-sm-9">
                                        <select name="type" class="form-control">
                                            <?php if($type == 'Single'){ ?>
                                            <option selected=""><?= $option_single ?></option>
                                            <?php } else{ ?>
                                            <option><?= $option_single ?></option>
                                            <?php } ?>

                                            <?php if($type=='Multi'){ ?>
                                            <option selected><?= $option_multi ?></option>
                                            <?php } else{  ?>
                                            <option><?= $option_multi ?></option>
                                            <?php } ?>

                                            <?php if($type=='Corporate'){ ?>
                                            <option><?= $option_corporate ?></option>
                                            <?php } else{  ?>
                                            <option><?= $option_corporate ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>                         

                                <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-name"><?= $entry_about_us ?></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" cols="50" name="about_us"><?php echo $about_us; ?></textarea>
                                        <?php if ($error_about_us) { ?>
                                        <div class="text-danger"><?php echo $error_about_us; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>                          

                                <!-- <div class="form-group required">
                                    <div class="col-sm-2"></div>
                                    <label for="agree" class="">                                                
                                        <input class="checkbox-inline" value="1" id="agree" type="checkbox" name="agree" <?php if($agree) echo 'checked'; ?> />                                                  
                                               <span style="font-weight: normal;"><?= $text_agree ?></span>
                                        <a target="_blank" alt="Terms & Conditions" href="<?= $terms_link ?>">
                                            <b><?= $text_terms ?></b>
                                        </a>
                                        <?php if ($error_agree) { ?>
                                        <div class="text-danger"><?php echo $error_agree; ?></div>
                                        <?php } ?>
                                    </label>                                    
                                </div> -->
                                

                            </div><!-- END .col-lg-6 -->

                                 <div class="form-group">
                                        <label class="col-md-4 control-label sr-only" for="submit">submit</label>
                                        <div class="col-md-12">
                                            <button id="submit" name="submit" class="btn btn-default btn-block btn-lg start-shopping" onclick="load()">
                                            <span class="submit-button-text"><?= $text_submit_details?></span>
                                            <div class="loader" style="display: none;"></div>
                                            </button>
                                        </div>
                                    </div>
                                                        </form>
                                                                       
                                </div>
                            </div>
                            <p><?= $text_have_account ?>  <a href="<?= $admin_link?> "><?= $text_login ?></a></p>
                        </div>
                        <div class="store-footer">
                            <p> <?= $text_increase_sales?> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $footer ?>

<script type="text/javascript">
    
    function load() {
        $('.submit-button-text').html('');
        $('.loader').show();    
    }
    
</script>





</body>
</html>
