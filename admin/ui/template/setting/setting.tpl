<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-local" data-toggle="tab"><?php echo $tab_local; ?></a></li>
                        <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
                        <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
                        <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
                        <li><a href="#tab-mail" data-toggle="tab"><?php echo $tab_mail; ?></a></li>
                        <li><a href="#tab-seo" data-toggle="tab"><?php echo $tab_seo; ?></a></li>
                        <li><a href="#tab-cache" data-toggle="tab"><?php echo $tab_cache; ?></a></li>
                        <li><a href="#tab-security" data-toggle="tab"><?php echo $tab_security; ?></a></li>
                        <li><a href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
                        <li><a href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li>
                        <li><a href="#tab-home" data-toggle="tab"><?php echo $tab_homepage; ?></a></li>
                        <li><a href="#tab-api" data-toggle="tab"><?php echo $tab_api; ?></a></li>
                        <!-- <li><a href="#tab-social" data-toggle="tab"><?php echo $tab_social; ?></a></li> -->
                        <li><a href="#tab-app-settings" data-toggle="tab"><?php echo $tab_app_settings; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab-home">
                        <fieldset>
                            <legend>Social Links</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_fb_link ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_facebook" value="<?php echo $config_facebook; ?>" placeholder="Facebook" class="form-control" />
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_twitter_link ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_twitter" value="<?php echo $config_twitter; ?>" placeholder="Twitter" class="form-control" />
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_google_link ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_google" value="<?php echo $config_google; ?>" placeholder="Google" class="form-control" />
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_youtube_link ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_youtube" value="<?php echo $config_youtube; ?>" placeholder="Youtube" class="form-control" />
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_instagram_link ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_instagram" value="<?php echo $config_instagram; ?>" placeholder="Instagram" class="form-control" />
                                </div>
                            </div> 
                        </fieldset>

                            <fieldset>
                                <legend><?= $text_footer ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $entry_android_link ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_android_app_link" value="<?php echo $config_android_app_link; ?>" placeholder="Android app link" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_iOS_link ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_apple_app_link" value="<?php echo $config_apple_app_link; ?>" placeholder="Apple app link" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-image"><?= $entry_promo_image ?></label>
                                    <div class="col-sm-10">
                                        <a href="" id="config_promo_app_image_thumb" data-toggle="image" class="img-thumbnail">
                                            <img src="<?php echo $promo_app_image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                        </a>
                                        <input type="hidden" name="config_promo_app_image" value="<?php echo $config_promo_app_image; ?>" id="config_promo_app_image" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-image"><?= $entry_promo_image ?></label>
                                    <div class="col-sm-10">
                                        <a href="" id="config_map_image_thumb" data-toggle="image" class="img-thumbnail">
                                            <img src="<?php echo $map_image_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                        </a>
                                        <input type="hidden" name="config_map_image" value="<?php echo $config_map_image; ?>" id="config_map_image" />
                                    </div>
                                </div>

                            

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-mail-protocol"><span data-toggle="tooltip" title="<?php echo $help_mail_protocol; ?>"><?php echo "About us type"; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_footer_text) { ?>
                                            <input type="radio" name="config_footer_text" value="1" checked="checked" />
                                            <?php echo $text_footer_text; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_footer_text" value="1" />
                                            <?php echo $text_footer_text; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_footer_text) { ?>
                                            <input type="radio" name="config_footer_text" value="0" checked="checked" />
                                            <?php echo $text_footer_video; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_footer_text" value="0" />
                                            <?php echo $text_footer_video; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-aboutus"><?php echo $entry_aboutus; ?></label>
                                    <div class="col-sm-10" id="input-aboutus">
                                        <textarea name="config_aboutus" placeholder="<?php echo $entry_aboutus; ?>" rows="5" id="input-aboutus" class="form-control"><?php echo $config_aboutus; ?></textarea disabled="true">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-information_url"><?php echo $entry_information_url; ?></label>
                                    <div class="col-sm-10">
                                        <select name="config_information_url" id="input-information_url" class="form-control">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($informations as $information) { ?>
                                            <?php if ($information['information_id'] == $config_information_url) { ?>
                                            <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-footer_video_link"><?php echo $entry_footer_video_link; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_footer_video_link" placeholder="<?php echo $entry_footer_video_link; ?>" id="input-footer_video_link" class="form-control" value="<?php echo $config_footer_video_link; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-footer-image"><?php echo $entry_footer_image; ?></label>
                                    <div class="col-sm-10" id="thumb-footer-image"><a href="" id="thumb-footer-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $footer_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_footer_thumb" value="<?php echo $config_footer_thumb; ?>" id="input-footer-image" />
                                    </div>
                                </div>
                            </fieldset>

                        </div>

                        <div class="tab-pane" id="tab-app-settings">

                        	<fieldset>
                            <legend>Force App Updates</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_update_android ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_update_android" class="form-control" value="<?php echo $config_update_android; ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-name"><?= $entry_update_ios ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_update_ios" class="form-control" value="<?php echo $config_update_ios; ?>"/>
                                </div>
                            </div>

                        </fieldset>
                            
                            <fieldset>
                                <legend><?= $text_seller ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_seller_app_key ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_seller_api_key" value="<?php echo $config_seller_api_key; ?>" placeholder="Seller API key" class="form-control" />
                                    </div>
                                </div>
                            </fieldset> 
                            <fieldset>
                                <legend><?= $text_view_map ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_view_map ?></label>
                                    <!-- <div class="col-sm-10">
                                        <select name="config_view_map" id="input-sendy-status" class="form-control">
                                            <?php if ($config_view_map) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_view_map) { ?>
                                            <input type="radio" name="config_view_map" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_view_map" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_view_map) { ?>
                                            <input type="radio" name="config_view_map" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_view_map" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset> 
                        </div>

                        <div class="tab-pane" id="tab-api">
                            <!-- <fieldset>
                                <legend><?= $text_driver ?></legend>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_charge ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_dri_charge" value="<?php echo $config_dri_charge; ?>" placeholder="Driver charge" class="form-control" />
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_max_order_driver ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_dri_max_order" value="<?php echo $config_dri_max_order; ?>" placeholder="Max order per driver" class="form-control" />
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <?= $entry_auto_assign ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_dri_auto_assign ) { ?>
                                            <input type="radio" name="config_dri_auto_assign" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_dri_auto_assign" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_dri_auto_assign) { ?>
                                            <input type="radio" name="config_dri_auto_assign" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_dri_auto_assign" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name">
                                        <?= $entry_customer_sms ?>
                                        <div class="help-block">
                                            {{driver}},<br />
                                            {{customer}},<br />
                                            {{order_id}}
                                        </div>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea name="config_dri_cust_sms" placeholder="Cutomer notification SMS" class="form-control"><?php echo $config_dri_cust_sms; ?></textarea>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name">
                                        <?= $entry_driver_sms ?>
                                        <div class="help-block">
                                            {{firstname}},<br />
                                            {{lastname}},<br />
                                            {{amount}},<br />
                                            {{order_id}}
                                        </div>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea name="config_dri_wallet_sms" placeholder="Driver wallet SMS" class="form-control"><?php echo $config_dri_wallet_sms; ?></textarea>
                                    </div>
                                </div>                                
                            </fieldset>
                            <fieldset>
                                <legend><?= $text_google ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_client_id ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_google_client_id" value="<?php echo $config_google_client_id; ?>" placeholder="Client ID" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_secret ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_google_client_secret" value="<?php echo $config_google_client_secret; ?>" placeholder="Secret" class="form-control" />
                                    </div>
                                </div>  
                            </fieldset>
                            <fieldset>
                                <legend><?= $text_facebook ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_application_id ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_fb_app_id" value="<?php echo $config_fb_app_id; ?>" placeholder="FB App ID" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_fb_secret ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_fb_secret" value="<?php echo $config_fb_secret; ?>" placeholder="FB Secret" class="form-control" />
                                    </div>
                                </div>  
                            </fieldset>   -->
                            <fieldset>
                                <legend><?= $text_deliversystem ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_delivery_status_webhook ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_delivery_status_webhook" value="<?php echo $config_delivery_status_webhook; ?>" placeholder="Delivery Status Webhook" class="form-control" disabled/>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_delivery_username ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_delivery_username" value="<?php echo $config_delivery_username; ?>" placeholder="Delivery System Username" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_delivery_secret ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_delivery_secret" value="<?php echo $config_delivery_secret; ?>" placeholder="Delivery System Secret" class="form-control" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"> Stripe Account</label>
                                    <div class="col-sm-10">
                                        

                                        <?php if($stripe_info_exists) { ?>

                                                <div id="stripe_user_id_div">
                                                    <span> Stripe User Id: <b> <?= $stripe_info['stripe_user_id'] ?> </b></span>
                                                    <span> 
                                                        <button type="button" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger" id="stripe_disconnect"> Disconnect Account </button>
                                                    </span>
                                                </div>
                                                

                                                
                                        <?php } else { ?>
                                            <a  onclick="window.open('https://connect.stripe.com/oauth/authorize?response_type=code&client_id=<?= $publishable_key ?>&scope=read_write&state=deliverysystem')"  style="cursor: pointer;">
                                                <img src="<?= $stripe_image?>">   

                                                <!-- <input type="button" value="button name" onclick="window.open('http://www.website.com/page')" /> --> 
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_shopper_link ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_shopper_link" value="<?php echo $config_shopper_link; ?>" placeholder="Delivery system link" class="form-control" />
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-process-status">
                                        <span data-toggle="tooltip">
                                            <?= $entry_shipping_methods ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($shipping_methods as $shipping_method) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($shipping_method['code'], $config_delivery_shipping_methods_status)) { ?>
                                                    <input type="checkbox" name="config_delivery_shipping_methods_status[]" value="<?php echo $shipping_method['code']; ?>" checked="checked" />
                                                    <?php echo $shipping_method['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_delivery_shipping_methods_status[]" value="<?php echo $shipping_method['code']; ?>" />
                                                    <?php echo $shipping_method['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_shipped_status) { ?>
                                        <div class="text-danger"><?php echo $error_shipped_status; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-konduto-status"><?php echo $entry_delivery_status; ?></label>
                                    <!-- <div class="col-sm-10">
                                        <select name="config_deliver_system_status" id="input-konduto-status" class="form-control">
                                            <?php if ($config_deliver_system_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_deliver_system_status) { ?>
                                            <input type="radio" name="config_deliver_system_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_deliver_system_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_deliver_system_status) { ?>
                                            <input type="radio" name="config_deliver_system_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_deliver_system_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>

                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-konduto-status"><?php echo $entry_checkout_delivery_status; ?></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_checkout_deliver_system_status) { ?>
                                            <input type="radio" name="config_checkout_deliver_system_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_checkout_deliver_system_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_checkout_deliver_system_status) { ?>
                                            <input type="radio" name="config_checkout_deliver_system_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_checkout_deliver_system_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>

                                </div>
                            </fieldset> 
                            
                            <!-- <fieldset>
                                <legend><?= $text_sendy ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_public_key ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_public_key" value="<?php echo $config_sendy_public_key; ?>" placeholder="<?= $entry_sendy_public_key ?>" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_api_end ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_api_end" value="<?php echo $config_sendy_api_end; ?>" placeholder="<?= $entry_sendy_api_end ?>" class="form-control" />
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_mail_from_name ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_mail_from_name" value="<?php echo $config_sendy_mail_from_name; ?>" placeholder="<?= $entry_sendy_mail_from_name ?>" class="form-control" />
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_mail_from ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_mail_from" value="<?php echo $config_sendy_mail_from; ?>" placeholder="<?= $entry_sendy_mail_from ?>" class="form-control" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_db_host ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_db_host" value="<?php echo $config_sendy_db_host; ?>" placeholder="<?= $entry_sendy_db_host ?>" class="form-control" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_db_user ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_db_user" value="<?php echo $config_sendy_db_user; ?>" placeholder="<?= $entry_sendy_db_user ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_db_pass ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_db_pass" value="<?php echo $config_sendy_db_pass; ?>" placeholder="<?= $entry_sendy_db_pass ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_db_name ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_db_name" value="<?php echo $config_sendy_db_name; ?>" placeholder="<?= $entry_sendy_db_name ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_sendy_db_port ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sendy_db_port" value="<?php echo $config_sendy_db_port; ?>" placeholder="<?= $entry_sendy_db_port ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sendy-status"><?php echo $entry_bulk_mailer_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="config_sendy_status" id="input-sendy-status" class="form-control">
                                            <?php if ($config_sendy_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> 
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_sendy_status) { ?>
                                            <input type="radio" name="config_sendy_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_sendy_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_sendy_status) { ?>
                                            <input type="radio" name="config_sendy_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_sendy_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset> -->
                        </div>

                                

                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_name" value="<?php echo $config_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                    <?php if ($error_name) { ?>
                                    <div class="text-danger"><?php echo $error_name; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                             <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-store-latitude"><?php echo $entry_store_latitude; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_store_latitude" value="<?php echo $config_store_latitude; ?>" placeholder="<?php echo $entry_store_latitude; ?>" id="input-store-latitude" class="form-control" />
                                    <?php if ($error_store_latitude) { ?>
                                    <div class="text-danger"><?php echo $error_store_latitude; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-store-longitude"><?php echo $entry_store_longitude; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_store_longitude" value="<?php echo $config_store_longitude; ?>" placeholder="<?php echo $entry_store_longitude; ?>" id="input-store-longitude" class="form-control" />
                                    <?php if ($error_store_longitude) { ?>
                                    <div class="text-danger"><?php echo $error_store_longitude; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-package">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Only for payu package id prefix">
                                        <?= $entry_package_prefix ?>
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_package_prefix" value="<?php echo $config_package_prefix; ?>" placeholder="Package prefix" id="input-package" class="form-control" />
                                </div>
                            </div>  

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-shopper-group-ids">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Comma Separated User Group IDs">
                                        <?= $entry_delivery_boy ?>
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_shopper_group_ids" value="<?php echo $config_shopper_group_ids; ?>" placeholder="Enter Delivery Boy Group IDs" id="input-shopper-group-ids" class="form-control" />
                                    <?php if ($error_shopper_group_ids) { ?>
                                    <div class="text-danger"><?php echo $error_shopper_group_ids; ?></div>
                                    <?php } ?>
                                </div>
                            </div> -->

                            <input type="hidden" name="config_shopper_group_ids" value="12"/>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-vendor-group-ids">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Comma Separated User Group IDs">
                                        <?= $entry_vendor_group_ids ?>
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_vendor_group_ids" value="<?php echo $config_vendor_group_ids; ?>" placeholder="Enter Vendor Group IDs" id="input-vendor-group-ids" class="form-control" />
                                    <?php if ($error_vendor_group_ids) { ?>
                                    <div class="text-danger"><?php echo $error_vendor_group_ids; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-account-manager-group-id">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Account Manager Group ID">
                                        Account Manager Group ID
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_account_manager_group_id" value="<?php echo $config_account_manager_group_id; ?>" placeholder="Enter Account Manager Group ID" id="input-account-manager-group-id" class="form-control" />
                                    <?php if ($error_account_manager_group_id) { ?>
                                    <div class="text-danger"><?php echo $error_account_manager_group_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                             <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-customer-experience-group-id">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Customer Experience Group ID">
                                        Customer Experience Group ID
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_customer_experience_group_id" value="<?php echo $config_customer_experience_group_id; ?>" placeholder="Enter Account Manager Group ID" id="input-account-manager-group-id" class="form-control" />
                                    <?php if ($error_customer_experience_group_id) { ?>
                                    <div class="text-danger"><?php echo $error_customer_experience_group_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-farmer-id">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Farmer Group ID">
                                        Farmer Group ID
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_farmer_group_id" value="<?php echo $config_farmer_group_id; ?>" placeholder="Enter Farmer Group ID" id="input-farmer-group-id" class="form-control" />
                                    <?php if ($error_farmer_group_id) { ?>
                                    <div class="text-danger"><?php echo $error_farmer_group_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-active-store-id">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Active Store ID">
                                        Active Store ID
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_active_store_id" value="<?php echo $config_active_store_id; ?>" placeholder="Enter Active Store ID" id="input-active-store-id" class="form-control" />
                                    <?php if ($error_active_store_id) { ?>
                                    <div class="text-danger"><?php echo $error_active_store_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-active-store-minimum-order-amount">
                                    <span data-toggle="tooltip" data-container="#tab-general" title="Add Active Store Minimum Order Amount">
                                         Active Store Minimum Amount
                                    </span>    
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_active_store_minimum_order_amount" value="<?php echo $config_active_store_minimum_order_amount; ?>" placeholder="Enter Active Store Minimum Order Amount" id="input-active-store-minimum-order-amount" class="form-control" />
                                    <?php if ($error_active_store_minimum_order_amount) { ?>
                                    <div class="text-danger"><?php echo $error_active_store_minimum_order_amount; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-owner"><?php echo $entry_owner; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" placeholder="<?php echo $entry_owner; ?>" id="input-owner" class="form-control" />
                                    <?php if ($error_owner) { ?>
                                    <div class="text-danger"><?php echo $error_owner; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
                                <div class="col-sm-10">
                                    <textarea name="config_address" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $config_address; ?></textarea>
                                    <?php if ($error_address) { ?>
                                    <div class="text-danger"><?php echo $error_address; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_email" value="<?php echo $config_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                    <?php if ($error_email) { ?>
                                    <div class="text-danger"><?php echo $error_email; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                                <div  class="col-sm-10 input-group" style="left: 14px;">

                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
                                    <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" />
                                    <?php if ($error_telephone) { ?>
                                    <div class="text-danger"><?php echo $error_telephone; ?></div>
                                    <?php } ?>
                                </div>
                            </div>



                        <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-amitruck-url"><?php echo $entry_amitruck_url; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_amitruck_url" value="<?php echo $config_amitruck_url; ?>" placeholder="<?php echo $entry_amitruck_url; ?>" id="input-amitruck-url" class="form-control" />
                                    <?php if ($error_amitruck_url) { ?>
                                    <div class="text-danger"><?php echo $error_amitruck_url; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                             <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-amitruck-clientId"><?php echo $entry_amitruck_clientId; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_amitruck_clientId" value="<?php echo $config_amitruck_clientId; ?>" placeholder="<?php echo $entry_amitruck_clientId; ?>" id="input-amitruck-clientId" class="form-control" />
                                    <?php if ($error_amitruck_clientId) { ?>
                                    <div class="text-danger"><?php echo $error_amitruck_clientId; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                             <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-amitruck-clientSecret"><?php echo $entry_amitruck_clientSecret; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_amitruck_clientSecret" value="<?php echo $config_amitruck_clientSecret; ?>" placeholder="<?php echo $entry_amitruck_clientSecret; ?>" id="input-amitruck_clientSecret" class="form-control" />
                                    <?php if ($error_amitruck_clientSecret) { ?>
                                    <div class="text-danger"><?php echo $error_amitruck_clientSecret; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                                <div  class="col-sm-10 input-group" style="left: 14px;">

                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
                                    <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" 
                                    placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" />
                                   

                                </div>
                            </div>
                           

                            

                            <?php if ($locations) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_location; ?>"><?php echo $entry_location; ?></span></label>
                                <div class="col-sm-10">
                                    <?php foreach ($locations as $location) { ?>
                                    <div class="checkbox">
                                        <label>
                                            <?php if (in_array($location['location_id'], $config_location)) { ?>
                                            <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" checked="checked" />
                                            <?php echo $location['name']; ?>
                                            <?php } else { ?>
                                            <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" />
                                            <?php echo $location['name']; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="tab-local">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span title="" data-toggle="tooltip" data-original-title="(e.g., IN for India)"><?= $entry_country_code ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_country_code" value="<?php echo $config_country_code ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?= $entry_country ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_country" value="<?php echo $config_country ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span title="" data-toggle="tooltip" data-original-title="(e.g., CA for California)"><?= $entry_state_code ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_state_code" value="<?php echo $config_state_code; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?= $entry_state ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_state" value="<?php echo $config_state ?>" class="form-control" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-country"><?= $entry_city ?></label>
                                <div class="col-sm-10">
                                    <select name="config_city_id" id="input-country" class="form-control">
                                        <?php foreach ($cities as $city) { ?>
                                        <?php if ($city['city_id'] == $config_city_id) { ?>
                                        <option value="<?php echo $city['city_id']; ?>" selected="selected"><?php echo $city['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
                                <div class="col-sm-10">
                                <?php $languages ?>
                                    <select name="config_language" id="input-language" class="form-control">
                                        <?php foreach ($languages as $language) { ?>
                                        <?php if ($language['code'] == $config_language) { ?>
                                        <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-admin-language"><?php echo $entry_admin_language; ?></label>
                                <div class="col-sm-10">
                                    <select name="config_admin_language" id="input-admin-language" class="form-control">
                                        <?php foreach ($languages as $language) { ?>
                                        <?php if ($language['code'] == $config_admin_language) { ?>
                                        <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_currency" id="input-currency" class="form-control">
                                        <?php foreach ($currencies as $currency) { ?>
                                        <?php if ($currency['code'] == $config_currency) { ?>
                                        <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-timezone"><?= $entry_timezone ?></label>
                                <div class="col-sm-10">
                                    <select name="config_timezone" id="input-timezone" class="form-control">
                                        <?php foreach ($tzlist as $tz) { ?>
                                        <?php if ($tz == $config_timezone) { ?>
                                        <option value="<?php echo $tz; ?>" selected="selected"><?php echo $tz; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $tz; ?>"><?php echo $tz; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_currency_auto; ?>"><?php echo $entry_currency_auto; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_currency_auto) { ?>
                                        <input type="radio" name="config_currency_auto" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_currency_auto" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_currency_auto) { ?>
                                        <input type="radio" name="config_currency_auto" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_currency_auto" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-design">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-template"><?php echo $entry_template; ?></label>
                                <div class="col-sm-10">
                                    <select name="config_template" id="input-template" class="form-control">
                                        <?php foreach ($templates as $template) { ?>
                                        <?php if ($template == $config_template) { ?>
                                        <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <br />
                                    <img src="" alt="" id="template" class="img-thumbnail" /></div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-option">
                            <fieldset>
                                <legend><?php echo $text_common; ?></legend>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-text-editor"><span data-toggle="tooltip" title="<?php echo $help_text_editor; ?>"><?php echo $entry_text_editor; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_text_editor" id="input-text-editor" class="form-control">
                                            <?php  if ($config_text_editor == 'summernote') { ?>
                                            <option value="summernote" selected="selected"><?php echo $text_summernote; ?></option>
                                            <option value="tinymce"><?php echo $text_tinymce; ?></option>
                                            <?php } else { ?>
                                            <option value="summernote"><?php echo $text_summernote; ?></option>
                                            <option value="tinymce" selected="selected"><?php echo $text_tinymce; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-login-attempts">
                                        <?= $entry_zipcode_mask ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_zipcode_mask" value="<?php echo $config_zipcode_mask; ?>" placeholder="<?= $entry_zipcode_mask ?>" id="input-login-attempts" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-login-attempts">
                                        <?= $entry_telephone_mask ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_telephone_mask" value="<?php echo $config_telephone_mask; ?>" placeholder="<?= $entry_telephone_mask ?>" id="input-login-attempts" class="form-control" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-login-attempts">
                                        <?= $entry_tax_number_mask ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_taxnumber_mask" value="<?php echo $config_taxnumber_mask; ?>" placeholder="<?= $entry_tax_number_mask ?>" id="input-login-attempts" class="form-control" />
                                    </div>
                                </div>  

                                <!-- paymethog group -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-process-status">
                                        <span data-toggle="tooltip">
                                            <?= $entry_payment_methods ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($payment_methods as $payment_method) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($payment_method['code'], $config_payment_methods_status)) { ?>
                                                    <input type="checkbox" name="config_payment_methods_status[]" value="<?php echo $payment_method['code']; ?>" checked="checked" />
                                                    <?php echo $payment_method['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_payment_methods_status[]" value="<?php echo $payment_method['code']; ?>" />
                                                    <?php echo $payment_method['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_payment_methods) { ?>
                                        <div class="text-danger"><?php echo $error_payment_methods; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- paymethog group end -->

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-text-editor"><span data-toggle="tooltip" title="<?php echo $help_store_location; ?>"><?php echo $entry_store_location; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_store_location" id="input-text-editor" class="form-control">
                                            <?php  if ($config_store_location == 'zipcode') { ?>
                                            <option value="zipcode" selected="selected"><?php echo $text_zipcode; ?></option>
                                            <option value="autosuggestion"> Auto Suggestion</option>
                                            <?php } else { ?>
                                            <option value="zipcode"><?php echo $text_zipcode; ?></option>
                                            <option value="autosuggestion" selected="selected"> Auto Suggestion</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                
                                
                            </fieldset>         
                            <fieldset>
                                <legend><?php echo $text_product; ?></legend>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_product_limit; ?>"><?php echo $entry_product_limit; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_product_limit" value="<?php echo $config_product_limit; ?>" placeholder="<?php echo $entry_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                                        <?php if ($error_product_limit) { ?>
                                        <div class="text-danger"><?php echo $error_product_limit; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-app-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_app_product_limit; ?>"><?php echo $entry_app_product_limit; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_app_product_limit" value="<?php echo $config_app_product_limit; ?>" placeholder="<?php echo $entry_app_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                                        <?php if ($error_app_product_limit) { ?>
                                        <div class="text-danger"><?php echo $error_app_product_limit; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_product_description_popup; ?></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_product_description_display) { ?>
                                            <input type="radio" name="config_product_description_display" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_product_description_display" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_product_description_display) { ?>
                                            <input type="radio" name="config_product_description_display" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_product_description_display" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-list-description-limit"><span data-toggle="tooltip" title="<?php echo $help_product_description_length; ?>"><?php echo $entry_product_description_length; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_product_description_length" value="<?php echo $config_product_description_length; ?>" placeholder="<?php echo $entry_product_description_length; ?>" id="input-list-description-limit" class="form-control" />
                                        <?php if ($error_product_description_length) { ?>
                                        <div class="text-danger"><?php echo $error_product_description_length; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_limit_admin; ?>"><?php echo $entry_limit_admin; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_limit_admin" value="<?php echo $config_limit_admin; ?>" placeholder="<?php echo $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
                                        <?php if ($error_limit_admin) { ?>
                                        <div class="text-danger"><?php echo $error_limit_admin; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_product_approval; ?>"><?php echo $entry_product_approval; ?></span></label>
                                    <div class="col-sm-10">
                                        
                                        <select name="config_auto_approval_product" id="input-text-editor" class="form-control">
                                            <?php  if ($config_auto_approval_product) { ?>
                                            <option value="1" selected="selected"><?= $text_auto ?></option>
                                            <option value="0"><?= $text_manual ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?= $text_auto ?></option>
                                            <option value="0" selected="selected"><?= $text_manual ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_referral; ?></legend>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_referee_points; ?>"><?php echo $entry_referral_referee_points; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_referee_points" value="<?php echo $config_referee_points; ?>" placeholder="<?php echo $entry_referral_referee_points; ?>" id="input-catalog-limit" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_refered_points; ?>"><?php echo $entry_referral_refered_points; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_refered_points" value="<?php echo $config_refered_points; ?>" placeholder="<?php echo $entry_referral_refered_points; ?>" id="input-admin-limit" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_refer_type; ?></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_refer_type == 'reward') { ?>
                                            <input type="radio" name="config_refer_type" value="reward" checked="checked" />
                                            <?php echo $text_reward_type; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_refer_type" value="reward" />
                                            <?php echo $text_reward_type; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if ($config_refer_type == 'credit') { ?>
                                            <input type="radio" name="config_refer_type" value="credit" checked="checked" />
                                            <?php echo $text_credit_type; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_refer_type" value="credit" />
                                            <?php echo $text_credit_type; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                
                            </fieldset>                                                    
                            <fieldset>
                                <legend><?php echo $text_tax; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $entry_tax; ?></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_tax) { ?>
                                            <input type="radio" name="config_tax" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_tax" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_tax) { ?>
                                            <input type="radio" name="config_tax" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_tax" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $entry_price_inclusive_tax ?></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_inclusiv_tax) { ?>
                                            <input type="radio" name="config_inclusiv_tax" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_inclusiv_tax" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_inclusiv_tax) { ?>
                                            <input type="radio" name="config_inclusiv_tax" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_inclusiv_tax" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                

                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_account; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_customer_online; ?>"><?php echo $entry_customer_online; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_customer_online) { ?>
                                            <input type="radio" name="config_customer_online" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_customer_online" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_customer_online) { ?>
                                            <input type="radio" name="config_customer_online" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_customer_online" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?= $entry_address_check ?></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_address_check) { ?>
                                            <input type="radio" name="config_address_check" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_address_check" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_address_check) { ?>
                                            <input type="radio" name="config_address_check" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_address_check" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_customer_group_id" id="input-customer-group" class="form-control">
                                            <?php foreach ($customer_groups as $customer_group) { ?>
                                            <?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_account_return_status; ?>"><?php echo $entry_account_return_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_account_return_status == 'yes') { ?>
                                            <input type="radio" name="config_account_return_status" value="yes" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>

                                             <input type="radio" name="config_account_return_status" value="yes"  />
                                            <?php echo $text_yes; ?>

                                            
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if ($config_account_return_status == 'no') { ?>
                                               
                                                <input type="radio" name="config_account_return_status" value="no" checked="checked"  />
                                             <?php echo $text_no; ?>

                                            <?php } else { ?>

                                                <input type="radio" name="config_account_return_status" value="no" />
                                                <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_account_return_product_status; ?>"><?php echo $entry_account_return_product_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_account_return_product_status == 'yes') { ?>
                                            <input type="radio" name="config_account_return_product_status" value="yes" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>

                                             <input type="radio" name="config_account_return_product_status" value="yes"  />
                                            <?php echo $text_yes; ?>

                                            
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if ($config_account_return_product_status == 'no') { ?>
                                               
                                                <input type="radio" name="config_account_return_product_status" value="no" checked="checked"  />
                                             <?php echo $text_no; ?>

                                            <?php } else { ?>

                                                <input type="radio" name="config_account_return_product_status" value="no" />
                                                <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-member-group">
                                        <span data-toggle="tooltip" title="Customer Group ID for Membership Accounts">
                                            <?= $entry_member_customer_group_id ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="config_member_group_id" id="input-member-group" class="form-control">
                                            <?php foreach ($customer_groups as $customer_group) { ?>
                                            <?php if ($customer_group['customer_group_id'] == $config_member_group_id) { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-login-attempts">
                                        <?= $entry_membership_account_fee ?>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_member_account_fee" value="<?php echo $config_member_account_fee; ?>" placeholder="Member acount fee" id="input-login-attempts" class="form-control" />
                                        <?php if ($error_member_account_fee) { ?>
                                        <div class="text-danger"><?php echo $error_member_account_fee; ?></div>
                                        <?php } ?>                  
                                    </div>
                                </div>    

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_customer_group_display; ?>"><?php echo $entry_customer_group_display; ?></span></label>
                                    <div class="col-sm-10">
                                        <?php foreach ($customer_groups as $customer_group) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
                                                <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                                                <?php echo $customer_group['name']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                                                <?php echo $customer_group['name']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                        <?php if ($error_customer_group_display) { ?>
                                        <div class="text-danger"><?php echo $error_customer_group_display; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_customer_price; ?>"><?php echo $entry_customer_price; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_customer_price) { ?>
                                            <input type="radio" name="config_customer_price" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_customer_price" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_customer_price) { ?>
                                            <input type="radio" name="config_customer_price" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_customer_price" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-login-attempts"><span data-toggle="tooltip" title="<?php echo $help_login_attempts; ?>"><?php echo $entry_login_attempts; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_login_attempts" value="<?php echo $config_login_attempts; ?>" placeholder="<?php echo $entry_login_attempts; ?>" id="input-login-attempts" class="form-control" />
                                        <?php if ($error_login_attempts) { ?>
                                        <div class="text-danger"><?php echo $error_login_attempts; ?></div>
                                        <?php } ?>                  
                                    </div>
                                </div>                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-account"><span data-toggle="tooltip" title="<?php echo $help_account; ?>"><?php echo $entry_account; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_account_id" id="input-account" class="form-control">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($informations as $information) { ?>
                                            <?php if ($information['information_id'] == $config_account_id) { ?>
                                            <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_account_mail; ?>"><?php echo $entry_account_mail; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_account_mail) { ?>
                                            <input type="radio" name="config_account_mail" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_account_mail" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_account_mail) { ?>
                                            <input type="radio" name="config_account_mail" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_account_mail" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_checkout; ?></legend>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Checkout Question Enable"><?= $entry_checkout_question_enable ?> </span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_checkout_question_enabled) { ?>
                                            <input type="radio" name="config_checkout_question_enabled" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_checkout_question_enabled" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_checkout_question_enabled) { ?>
                                            <input type="radio" name="config_checkout_question_enabled" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_checkout_question_enabled" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <!-- Reward start -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span data-toggle="tooltip" title="Reward point value in currency">
                                            <?= $entry_reward_point_value ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_reward_value" value="<?php echo $config_reward_value; ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Reward point Enable"><?= $entry_reward_point_enable ?> </span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_reward_enabled) { ?>
                                            <input type="radio" name="config_reward_enabled" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_reward_enabled" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_reward_enabled) { ?>
                                            <input type="radio" name="config_reward_enabled" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_reward_enabled" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span data-toggle="tooltip" title="Reward point on signup">
                                            <?= $entry_reward_point_signup ?> 
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_reward_onsignup" value="<?php echo $config_reward_onsignup; ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Reward point on Order Total-Percentage or Fixed"><?= $entry_reward_point_total ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_reward_switch_order_value == 'p') { ?>
                                            <input type="radio" name="config_reward_switch_order_value" value="p" checked="checked" />
                                            Percentage
                                            <?php } else { ?>
                                            <input type="radio" name="config_reward_switch_order_value" value="p" />
                                            Percentage
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if ($config_reward_switch_order_value == 'f') { ?>
                                            <input type="radio" name="config_reward_switch_order_value" value="f" checked="checked" />
                                            Fixed
                                            <?php } else { ?>
                                            <input type="radio" name="config_reward_switch_order_value" value="f" />
                                            Fixed
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span data-toggle="tooltip" title="Reward point on signup">
                                            <?= $entry_reward_point_order_total ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_reward_on_order_total" value="<?php echo $config_reward_on_order_total; ?>" class="form-control" />
                                    </div>
                                </div>

                                <!-- Credit Start -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Credits Enable"><?= $entry_credit_point_enable ?> </span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_credit_enabled) { ?>
                                            <input type="radio" name="config_credit_enabled" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_credit_enabled" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_credit_enabled) { ?>
                                            <input type="radio" name="config_credit_enabled" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_credit_enabled" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span data-toggle="tooltip" title="Credits on signup">
                                            <?= $entry_credit_point_signup ?> 
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_credit_onsignup" value="<?php echo $config_credit_onsignup; ?>" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Credits on Order Total-Percentage or Fixed"><?= $entry_credit_point_total ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_credit_switch_order_value == 'p') { ?>
                                            <input type="radio" name="config_credit_switch_order_value" value="p" checked="checked" />
                                            Percentage
                                            <?php } else { ?>
                                            <input type="radio" name="config_credit_switch_order_value" value="p" />
                                            Percentage
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if ($config_credit_switch_order_value == 'f') { ?>
                                            <input type="radio" name="config_credit_switch_order_value" value="f" checked="checked" />
                                            Fixed
                                            <?php } else { ?>
                                            <input type="radio" name="config_credit_switch_order_value" value="f" />
                                            Fixed
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span data-toggle="tooltip" title="Credits on signup">
                                            <?= $entry_credit_point_order_total ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_credit_on_order_total" value="<?php echo $config_credit_on_order_total; ?>" class="form-control" />
                                    </div>
                                </div>

                                <!-- Credit end -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-invoice-prefix"><span data-toggle="tooltip" title="<?php echo $help_invoice_prefix; ?>"><?php echo $entry_invoice_prefix; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" placeholder="<?php echo $entry_invoice_prefix; ?>" id="input-invoice-prefix" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-api"><span data-toggle="tooltip" title="<?php echo $help_api; ?>"><?php echo $entry_api; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_api_id" id="input-api" class="form-control">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($apis as $api) { ?>
                                            <?php if ($api['api_id'] == $config_api_id) { ?>
                                            <option value="<?php echo $api['api_id']; ?>" selected="selected"><?php echo $api['username']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $api['api_id']; ?>"><?php echo $api['username']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>        
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_order_status_id" id="input-order-status" class="form-control">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-process-status">
                                        <span data-toggle="tooltip">
                                            <?= $entry_shipping_order_status ?>
                                        </span>
                                    </label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($order_status['order_status_id'], $config_shipped_status)) { ?>
                                                    <input type="checkbox" name="config_shipped_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_shipped_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_shipped_status) { ?>
                                        <div class="text-danger"><?php echo $error_shipped_status; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $help_processing_status; ?>"><?php echo $entry_processing_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($order_status['order_status_id'], $config_processing_status)) { ?>
                                                    <input type="checkbox" name="config_processing_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_processing_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_processing_status) { ?>
                                        <div class="text-danger"><?php echo $error_processing_status; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-refund-status"><span data-toggle="tooltip" title="<?php echo $help_refund_status; ?>"><?php echo $entry_refund_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($order_status['order_status_id'], $config_refund_status)) { ?>
                                                    <input type="checkbox" name="config_refund_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_refund_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_refund_status) { ?>
                                        <div class="text-danger"><?php echo $error_refund_status; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-ready_for_pickup-status"><span data-toggle="tooltip" title="<?php echo $help_ready_for_pickup_status; ?>"><?php echo $entry_ready_for_pickup; ?></span></label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($order_status['order_status_id'], $config_ready_for_pickup_status)) { ?>
                                                    <input type="checkbox" name="config_ready_for_pickup_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_ready_for_pickup_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_ready_for_pickup_status) { ?>
                                        <div class="text-danger"><?php echo $error_ready_for_pickup_status; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-complete-status"><span data-toggle="tooltip" title="<?php echo $help_complete_status; ?>"><?php echo $entry_complete_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($order_status['order_status_id'], $config_complete_status)) { ?>
                                                    <input type="checkbox" name="config_complete_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_complete_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                    <?php echo $order_status['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($error_complete_status) { ?>
                                        <div class="text-danger"><?php echo $error_complete_status; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_order_mail; ?>"><?php echo $entry_order_mail; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_order_mail) { ?>
                                            <input type="radio" name="config_order_mail" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_order_mail" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_order_mail) { ?>
                                            <input type="radio" name="config_order_mail" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_order_mail" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_multi_store; ?>"><?php echo $entry_multi_store_order; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_multi_store) { ?>
                                            <input type="radio" name="config_multi_store" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_multi_store" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_multi_store) { ?>
                                            <input type="radio" name="config_multi_store" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_multi_store" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>

                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_stock; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_stock_display; ?>"><?php echo $entry_stock_display; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_stock_display) { ?>
                                            <input type="radio" name="config_stock_display" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_stock_display" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_stock_display) { ?>
                                            <input type="radio" name="config_stock_display" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_stock_display" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_stock_warning; ?>"><?php echo $entry_stock_warning; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_stock_warning) { ?>
                                            <input type="radio" name="config_stock_warning" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_stock_warning" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_stock_warning) { ?>
                                            <input type="radio" name="config_stock_warning" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_stock_warning" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_stock_checkout; ?>"><?php echo $entry_stock_checkout; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_stock_checkout) { ?>
                                            <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_stock_checkout" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_stock_checkout) { ?>
                                            <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_stock_checkout" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_return; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-return"><span data-toggle="tooltip" title="<?php echo $help_return; ?>"><?php echo $entry_return; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_return_id" id="input-return" class="form-control">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($informations as $information) { ?>
                                            <?php if ($information['information_id'] == $config_return_id) { ?>
                                            <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-return-status"><span data-toggle="tooltip" title="<?php echo $help_return_status; ?>"><?php echo $entry_return_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_return_status_id" id="input-return-status" class="form-control">
                                            <?php foreach ($return_statuses as $return_status) { ?>
                                            <?php if ($return_status['return_status_id'] == $config_return_status_id) { ?>
                                            <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-config_complete_return_status_id"><span data-toggle="tooltip" title="<?php echo $help_complete_return_status; ?>"><?php echo $entry_complete_return_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_complete_return_status_id" id="input-config_complete_return_status_id" class="form-control">
                                            <?php foreach ($return_statuses as $return_status) { ?>
                                            <?php if ($return_status['return_status_id'] == $config_complete_return_status_id) { ?>
                                            <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-login-attempts"><span data-toggle="tooltip" title="Set timeout in seconds"><?php echo $entry_return_timeout; ?></span>
                                       
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_return_timeout" value="<?php echo $config_return_timeout;?>" class="form-control" /><span data-toggle="tooltip" title="Set timeout in seconds">(Set expiry timeout in seconds)</span>
                                    </div>
                                </div>  

                                
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_seller_term; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-return"><span data-toggle="tooltip" title="<?php echo $help_return; ?>"><?php echo $entry_seller; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_seller_id" id="input-return" class="form-control">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($informations as $information) { ?>
                                            <?php if ($information['information_id'] == $config_seller_id) { ?>
                                            <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_privacy_policy; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-return"><span data-toggle="tooltip" title="<?php echo $help_return; ?>"><?php echo $entry_policy_terms; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_privacy_policy_id" id="input-return" class="form-control">
                                            <option value="0"><?php echo $text_none; ?></option>
                                            <?php foreach ($informations as $information) { ?>
                                            <?php if ($information['information_id'] == $config_privacy_policy_id) { ?>
                                            <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                             <fieldset>
                                <legend><?php echo $text_offer; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-return"><span data-toggle="tooltip" title="<?php echo $help_offer; ?>"><?php echo $entry_offer; ?></span></label>
                                    <!-- <div class="col-sm-10">
                                        <select name="config_offer_status" id="input-offer-status" class="form-control">
                                            <?php if ($config_offer_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_offer_status) { ?>
                                            <input type="radio" name="config_offer_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_offer_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_offer_status) { ?>
                                            <input type="radio" name="config_offer_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_offer_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane" id="tab-image">

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="config_image" value="<?php echo $config_image; ?>" id="input-image" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
                                <div class="col-sm-10"><a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="input-logo" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_white_logo; ?></label>
                                <div class="col-sm-10"><a href="" id="white-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $white_logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="config_white_logo" value="<?php echo $config_white_logo; ?>" id="input-white-logo" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-icon"><?php echo $entry_icon; ?></label>
                                <div class="col-sm-10"><a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $icon; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="input-icon" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-icon"><?php echo $entry_small_icon; ?></label>
                                <div class="col-sm-10"><a href="" id="small_icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $small_icon; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="config_small_icon" value="<?php echo $config_small_icon; ?>" id="input-small-icon" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_image_category) { ?>
                                    <div class="text-danger"><?php echo $error_image_category; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_image_thumb; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_image_thumb) { ?>
                                    <div class="text-danger"><?php echo $error_image_thumb; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_zoomimage_thumb; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_zoomimage_thumb_width" value="<?php echo $config_zoomimage_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_zoomimage_thumb_height" value="<?php echo $config_zoomimage_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_zoomimage_thumb) { ?>
                                    <div class="text-danger"><?php echo $error_zoomimage_thumb; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-product-width"><?php echo $entry_image_product; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_image_product) { ?>
                                    <div class="text-danger"><?php echo $error_image_product; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-cart"><?php echo $entry_image_cart; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_image_cart) { ?>
                                    <div class="text-danger"><?php echo $error_image_cart; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_image_location; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_location_width" value="<?php echo $config_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_image_location_height" value="<?php echo $config_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_image_location) { ?>
                                    <div class="text-danger"><?php echo $error_image_location; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!--  -->

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_app_image_category; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_category_width" value="<?php echo $config_app_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_category_height" value="<?php echo $config_app_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_app_image_category) { ?>
                                    <div class="text-danger"><?php echo $error_app_image_category; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_app_image_thumb; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_thumb_width" value="<?php echo $config_app_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_thumb_height" value="<?php echo $config_app_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_app_image_thumb) { ?>
                                    <div class="text-danger"><?php echo $error_app_image_thumb; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-product-width"><?php echo $entry_app_image_product; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_product_width" value="<?php echo $config_app_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_product_height" value="<?php echo $config_app_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_app_image_product) { ?>
                                    <div class="text-danger"><?php echo $error_app_image_product; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-cart"><?php echo $entry_app_image_cart; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_cart_width" value="<?php echo $config_app_image_cart_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_cart_height" value="<?php echo $config_app_image_cart_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_app_image_cart) { ?>
                                    <div class="text-danger"><?php echo $error_app_image_cart; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_app_image_location; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_location_width" value="<?php echo $config_app_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_image_location_height" value="<?php echo $config_app_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>
                                    </div>
                                    <?php if ($error_app_image_location) { ?>
                                    <div class="text-danger"><?php echo $error_app_image_location; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_app_notice_image_location; ?></label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        
                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_notice_image_location_height" value="<?php echo $config_app_notice_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                        </div>

                                        <div class="col-sm-6">
                                            <input type="text" name="config_app_notice_image_location_width" value="<?php echo $config_app_notice_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                                        </div>
                                        
                                    </div>
                                    <?php if ($error_app_notice_image_location) { ?>
                                    <div class="text-danger"><?php echo $error_app_notice_image_location; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                            <!--  -->
                        </div>
                        <div class="tab-pane" id="tab-mail">

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-from-email"><?php echo $entry_from_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_from_email" value="<?php echo $config_from_email; ?>" placeholder="<?php echo $entry_from_email; ?>" id="input-email" class="form-control" />
                                    <?php if ($error_from_email) { ?>
                                    <div class="text-danger"><?php echo $error_from_email; ?></div>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-mail-protocol"><span data-toggle="tooltip" title="<?php echo $help_mail_protocol; ?>"><?php echo $entry_mail_protocol; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_mail[protocol]" id="input-mail-protocol" class="form-control">
                                        <?php if ($config_mail_protocol == 'aws') { ?>
                                        <option value="aws" selected="selected"><?php echo $text_aws; ?></option>
                                        <?php } else { ?>
                                        <option value="aws"><?php echo $text_aws; ?></option>
                                        <?php } ?>

                                        <?php if ($config_mail_protocol == 'phpmail') { ?>
                                        <option value="phpmail" selected="selected"><?php echo $text_phpmail; ?></option>
                                        <?php } else { ?>
                                        <option value="phpmail"><?php echo $text_phpmail; ?></option>
                                        <?php } ?>
                                        <?php if ($config_mail_protocol == 'sendmail') { ?>
                                        <option value="sendmail" selected="selected"><?php echo $text_sendmail; ?></option>
                                        <?php } else { ?>
                                        <option value="sendmail"><?php echo $text_sendmail; ?></option>
                                        <?php } ?>
                                        <?php if ($config_mail_protocol == 'smtp') { ?>
                                        <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                                        <?php } else { ?>
                                        <option value="smtp"><?php echo $text_smtp; ?></option>
                                        <?php } ?>

                                        <?php if ($config_mail_protocol == 'mailgun') { ?>
                                        <option value="mailgun" selected="selected"><?php echo $text_mailgun; ?></option>
                                        <?php } else { ?>
                                        <option value="mailgun"><?php echo $text_mailgun; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                            <!-- maigun start -->

                            <!--

                             <input type="text" name="config_sms_sender_id" value="<?php echo $config_sms_sender_id; ?>" id="input-smtp-port" class="form-control" 
                                     <?php echo ($config_sms_protocol == 'twilio') ? '' : 'readonly="readonly"' ?> />

                                      -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-mailgun-hostname"><?php echo $entry_mailgun_key; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[mailgun]" value="<?php echo $config_mailgun; ?>" <?php echo ($config_mail_protocol == 'mailgun') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_mailgun_key; ?>" id="input-mailgun" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-mailgun-hostname"><?php echo $entry_mailgun_domain; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[mailgun_domain]" value="<?php echo $config_mailgun_domain; ?>" <?php echo ($config_mail_protocol == 'mailgun') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_mailgun_domain; ?>" id="input-mailgun" class="form-control" />
                                </div>
                            </div>

                            <!-- end  -->

                            <!-- 3 parameters to be 1) Region 2 ) AWS AccessID 3) AWS Secret key -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-hostname"><?php echo $entry_aws_region; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[aws_region]" value="<?php echo $config_aws_region; ?>" <?php echo ($config_mail_protocol == 'aws') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_aws_region; ?>" id="input-aws-region" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-aws-access_id"><?php echo $entry_aws_access_id; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[aws_access_id]" value="<?php echo $config_aws_access_id; ?>" <?php echo ($config_mail_protocol == 'aws') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_aws_access_id; ?>" id="input-aws-access_id" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-aws-key"><?php echo $entry_aws_secret_key; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[aws_secret_key]" value="<?php echo $config_aws_secret_key; ?>" <?php echo ($config_mail_protocol == 'aws') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_aws_secret_key; ?>" id="input-aws-key" class="form-control" />
                                </div>
                            </div>
                            <!-- AWS end -->


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-mail-sendmail_path"><span data-toggle="tooltip" title="<?php echo $help_mail_sendmail_path; ?>"><?php echo $entry_mail_sendmail_path; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[sendmail_path]" value="<?php echo $config_mail_sendmail_path; ?>" <?php echo ($config_mail_protocol == 'sendmail') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_mail_sendmail_path; ?>" id="input-mail-sendmail_path" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-hostname"><?php echo $entry_smtp_hostname; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[smtp_hostname]" value="<?php echo $config_smtp_hostname; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_smtp_hostname; ?>" id="input-smtp-hostname" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-username"><?php echo $entry_smtp_username; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[smtp_username]" value="<?php echo $config_smtp_username; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_smtp_username; ?>" id="input-smtp-username" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-password"><?php echo $entry_smtp_password; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[smtp_password]" value="<?php echo $config_smtp_password; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_smtp_password; ?>" id="input-smtp-password" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-port"><?php echo $entry_smtp_port; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_mail[smtp_port]" value="<?php echo $config_smtp_port; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'readonly="readonly"' ?> placeholder="<?php echo $entry_smtp_port; ?>" id="input-smtp-port" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="<?php echo $help_mail_smtp_encryption; ?>"><?php echo $entry_smtp_encryption; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_mail[smtp_encryption]" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'readonly="readonly"' ?> id="input-mail-smtp-encryption" class="form-control">
                                            <?php if ($config_smtp_encryption == 'none') { ?>
                                            <option value="none" selected="selected"><?php echo $text_smtp_encryption_n; ?></option>
                                        <?php } else { ?>
                                        <option value="none"><?php echo $text_smtp_encryption_n; ?></option>
                                        <?php } ?>
                                        <?php if ($config_smtp_encryption == 'ssl') { ?>
                                        <option value="ssl" selected="selected"><?php echo $text_smtp_encryption_s; ?></option>
                                        <?php } else { ?>
                                        <option value="ssl"><?php echo $text_smtp_encryption_s; ?></option>
                                        <?php } ?>
                                        <?php if ($config_smtp_encryption == 'tls') { ?>
                                        <option value="tls" selected="selected"><?php echo $text_smtp_encryption_t; ?></option>
                                        <?php } else { ?>
                                        <option value="tls"><?php echo $text_smtp_encryption_t; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-alert-email"><span data-toggle="tooltip" title="<?php echo $help_mail_alert; ?>"><?php echo $entry_mail_alert; ?></span></label>
                                <div class="col-sm-10">
                                    <textarea name="config_mail_alert" rows="5" placeholder="<?php echo $entry_mail_alert; ?>" id="input-alert-email" class="form-control"><?php echo $config_mail_alert; ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <h4><?= $text_sms_gateway_setup ?></h4>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone_country_code; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_telephone_code" value="<?php echo $config_telephone_code; ?>" placeholder="<?php echo $entry_telephone_country_code; ?>" id="input-telephone" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-sms-protocol"><span data-toggle="tooltip" title="<?php echo $help_sms_protocol; ?>"><?php echo $entry_sms_protocol; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="config_sms_protocol" id="input-sms-protocol" class="form-control">
                                        <?php if ($config_sms_protocol == 'africastalking') { ?>
                                        <option value="africastalking" selected="selected">Africa's Talking</option>
                                        <?php } else {?>
                                        <option value="africastalking">Africa's Talking</option>
                                        <?php } ?>

                                        <?php if ($config_sms_protocol == 'twilio') { ?>
                                        <option value="twilio" selected="selected"><?php echo $text_twilio; ?></option>
                                        <?php } else { ?>
                                        <option value="twilio"><?php echo $text_twilio; ?></option>
                                        <?php } ?>

                                        <?php if ($config_sms_protocol == 'zenvia') { ?>
                                        <option value="zenvia" selected="selected"><?php echo $text_zenvia; ?></option>
                                        <?php } else {?>
                                        <option value="zenvia"><?php echo $text_zenvia; ?></option>
                                        <?php } ?>

                                        <?php if ($config_sms_protocol == 'wayhub') { ?>
                                        <option value="wayhub" selected="selected"><?php echo $text_wayhub; ?></option>
                                        <?php } else {?>
                                        <option value="wayhub"><?php echo $text_wayhub; ?></option>
                                        <?php } ?>

                                        <?php if ($config_sms_protocol == 'uwaziimobile') { ?>
                                        <option value="uwaziimobile" selected="selected"><?php echo $text_uwaziimobile; ?></option>
                                        <?php } else {?>
                                        <option value="uwaziimobile"><?php echo $text_uwaziimobile; ?></option>
                                        <?php } ?>

                                        <?php if ($config_sms_protocol == 'awssns') { ?>
                                        <option value="awssns" selected="selected">AWS SNS</option>
                                        <?php } else {?>
                                        <option value="awssns">AWS SNS</option>
                                        <?php } ?>


                                    </select>
                                </div>
                            </div>
                            <!-- Africa's Talking -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Africa's Talking username">Africa's Talking Username</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_africastalking_sms_username" value="<?php echo $config_africastalking_sms_username; ?>" id="config_africastalking_sms_username" class="form-control" <?php echo ($config_sms_protocol == 'africastalking') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Africa's Talking API Key">Africa's Talking API Key</span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_africastalking_sms_api_key" value="<?php echo $config_africastalking_sms_api_key; ?>" id="config_africastalking_sms_api_key" class="form-control" <?php echo ($config_sms_protocol == 'africastalking') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                        
                            <!-- Twilio -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Sms Sender id"><?= $entry_sms_id ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_sms_sender_id" value="<?php echo $config_sms_sender_id; ?>" id="input-smtp-port" class="form-control" 
                                     <?php echo ($config_sms_protocol == 'twilio') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Sms Sender Username"><?= $entry_sms_token ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_sms_token" value="<?php echo $config_sms_token; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'twilio') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Sms Sender Password"><?= $entry_sms_number ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_sms_number" value="<?php echo $config_sms_number; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'twilio') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                            <!-- zenvia block -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Zenvia Sms Sender id"><?= $entry_zenvia_sms_id ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_zenvia_sms_sender_id" value="<?php echo $config_zenvia_sms_sender_id; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'zenvia') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Zenvia Sms Sender Username"><?= $entry_zenvia_sms_token ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_zenvia_sms_token" value="<?php echo $config_zenvia_sms_token; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'zenvia') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Zenvia Sms Sender Password"><?= $entry_zenvia_sms_number ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_zenvia_sms_number" value="<?php echo $config_zenvia_sms_number; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'zenvia') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>

                            <!-- wayhub block -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Wayhub Sms Sender id"><?= $entry_wayhub_sms_id ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_wayhub_sms_sender_id" value="<?php echo $config_wayhub_sms_sender_id; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'wayhub') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="wayhub Sms Sender Username"><?= $entry_wayhub_sms_token ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_wayhub_sms_token" value="<?php echo $config_wayhub_sms_token; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'wayhub') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Wayhub Sms Sender Password"><?= $entry_wayhub_sms_number ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_wayhub_sms_number" value="<?php echo $config_wayhub_sms_number; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'wayhub') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>

                            <!-- uwaziimobile block -->
                            
                            

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="uwaziimobile Sms Sender Username"><?= $entry_uwaziimobile_sms_token ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_uwaziimobile_sms_token" value="<?php echo $config_uwaziimobile_sms_token; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'uwaziimobile') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="uwaziimobile Sms Sender Password"><?= $entry_uwaziimobile_sms_number ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_uwaziimobile_sms_sender_id" value="<?php echo $config_uwaziimobile_sms_sender_id; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'uwaziimobile') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-smtp-encryption"><span data-toggle="tooltip" title="Uwaziimobile Sms Sender id"><?= $entry_uwaziimobile_sms_id ?></span></label>
                                <div class="col-sm-10">
                                     <input type="text" name="config_uwaziimobile_sms_number" value="<?php echo $config_uwaziimobile_sms_number; ?>" id="input-smtp-port" class="form-control" <?php echo ($config_sms_protocol == 'uwaziimobile') ? '' : 'readonly="readonly"' ?> />
                                </div>
                            </div>

                            <!-- uwaziimobile end -->

                            <hr>
                            
                            <h4>Test SMS</h4>
                            <div id="test-sms">
                                <div class="form-group">
                                        <label class="col-sm-2 control-label" for="phoneNumber">Phone Number</label>
                                        <input type="text" class="form-control" name="to" id="phoneNumber" placeholder="Enter number" />
                                </div>
                                <div class="form-group">
                                        <label class="col-sm-2 control-label" for="smsMessage">Message</label>
                                        <textarea name="message" class="form-control" id="smsMessage" cols="45" rows="15"></textarea>
                                    </div>
                                <div class="form-group">
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-10">
                                        <button type="button" id="button-send-sms" onclick="testSms()" data-loading-text="Sending..." class="btn btn-primary"><i class="fa fa-send"></i>Send SMS</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="tab-seo">
                            <fieldset>
                                <legend><?php echo $text_seo_urls; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_seo_url) { ?>
                                            <input type="radio" name="config_seo_url" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_seo_url" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_seo_url) { ?>
                                            <input type="radio" name="config_seo_url" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_seo_url" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_rewrite; ?>"><?php echo $entry_seo_rewrite; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_rewrite" value="1" <?php echo ($config_seo_rewrite) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_rewrite" value="0" <?php echo (!$config_seo_rewrite) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_suffix; ?>"><?php echo $entry_seo_suffix; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_suffix" value="1" <?php echo ($config_seo_suffix) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_suffix" value="0" <?php echo (!$config_seo_suffix) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-seo-category"><span data-toggle="tooltip" title="<?php echo $help_seo_category; ?>"><?php echo $entry_seo_category; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_seo_category" id="input-seo-category" class="form-control">
                                            <option value="0" <?php echo ($config_seo_category == '0') ? 'selected="selected"' : ''; ?> ><?php echo $text_no; ?></option>
                                            <option value="last" <?php echo ($config_seo_category == 'last') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_category_last; ?></option>
                                            <option value="all" <?php echo ($config_seo_category == 'all') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_category_all; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_translate; ?>"><?php echo $entry_seo_translate; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_translate" value="1" <?php echo ($config_seo_translate) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_translate" value="0" <?php echo (!$config_seo_translate) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_lang_code; ?>"><?php echo $entry_seo_lang_code; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_lang_code" value="1" <?php echo ($config_seo_lang_code) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_lang_code" value="0" <?php echo (!$config_seo_lang_code) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_canonical; ?>"><?php echo $entry_seo_canonical; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_canonical" value="1" <?php echo ($config_seo_canonical) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_canonical" value="0" <?php echo (!$config_seo_canonical) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-seo-www-red"><span data-toggle="tooltip" title="<?php echo $help_seo_www_red; ?>"><?php echo $entry_seo_www_red; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_seo_www_red" id="input-seo-www-red" class="form-control">
                                            <option value="0" <?php echo ($config_seo_www_red == '0') ? 'selected="selected"' : ''; ?> ><?php echo $text_no; ?></option>
                                            <option value="with" <?php echo ($config_seo_www_red == 'with') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_www_red_with; ?></option>
                                            <option value="non" <?php echo ($config_seo_www_red == 'non') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_www_red_non; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_nonseo_red; ?>"><?php echo $entry_seo_nonseo_red; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_nonseo_red" value="1" <?php echo ($config_seo_nonseo_red) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_seo_nonseo_red" value="0" <?php echo (!$config_seo_nonseo_red) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_metadata; ?></legend>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_meta_title" value="<?php echo $config_meta_title; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                                        <?php if ($error_meta_title) { ?>
                                        <div class="text-danger"><?php echo $error_meta_title; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-meta-title-add"><span data-toggle="tooltip" title="<?php echo $help_meta_title_add; ?>"><?php echo $entry_meta_title_add; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_meta_title_add" id="input-meta-title-add" class="form-control">
                                            <option value="0" <?php echo ($config_meta_title_add == '0') ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                            <option value="pre" <?php echo ($config_meta_title_add == 'pre') ? 'selected="selected"' : ''; ?>><?php echo $text_meta_title_add_pre; ?></option>
                                            <option value="post" <?php echo ($config_meta_title_add == 'post') ? 'selected="selected"' : ''; ?>><?php echo $text_meta_title_add_post; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="config_meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo $config_meta_description; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="config_meta_keyword" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo $config_meta_keyword; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-meta-generator"><span data-toggle="tooltip" title="<?php echo $help_meta_generator; ?>"><?php echo $entry_meta_generator; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_meta_generator" value="<?php echo $config_meta_generator; ?>" placeholder="<?php echo $entry_meta_generator; ?>" id="input-meta-generator" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-meta-googlekey"><span data-toggle="tooltip" title="<?php echo $help_meta_googlekey; ?>"><?php echo $entry_meta_googlekey; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_meta_googlekey" value="<?php echo $config_meta_googlekey; ?>" placeholder="<?php echo $entry_meta_googlekey; ?>" id="input-meta-googlekey" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-meta-alexakey"><span data-toggle="tooltip" title="<?php echo $help_meta_alexakey; ?>"><?php echo $entry_meta_alexakey; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_meta_alexakey" value="<?php echo $config_meta_alexakey; ?>" placeholder="<?php echo $entry_meta_alexakey; ?>" id="input-meta-alexakey" class="form-control" />
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="hidden">
                                <legend><?php echo $text_seo_sitemap; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sitemap-all"><span data-toggle="tooltip" title="<?php echo $help_sitemap_all; ?>"><?php echo $entry_sitemap_all; ?></span></label>
                                    <div class="col-sm-10" style="margin-top: 9px;">
                                        <a href="<?php echo $config_sitemap_all; ?>" target="_blank"><?php echo $config_sitemap_all; ?></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sitemap-products"><span data-toggle="tooltip" title="<?php echo $help_sitemap_products; ?>"><?php echo $entry_sitemap_products; ?></span></label>
                                    <div class="col-sm-10" style="margin-top: 9px;">
                                        <a href="<?php echo $config_sitemap_products; ?>" target="_blank"><?php echo $config_sitemap_products; ?></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sitemap-categories"><span data-toggle="tooltip" title="<?php echo $help_sitemap_categories; ?>"><?php echo $entry_sitemap_categories; ?></span></label>
                                    <div class="col-sm-10" style="margin-top: 9px;">
                                        <a href="<?php echo $config_sitemap_categories; ?>" target="_blank"><?php echo $config_sitemap_categories; ?></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sitemap-manufacturers"><span data-toggle="tooltip" title="<?php echo $help_sitemap_manufacturers; ?>"><?php echo $entry_sitemap_manufacturers; ?></span></label>
                                    <div class="col-sm-10" style="margin-top: 9px;">
                                        <a href="<?php echo $config_sitemap_manufacturers; ?>" target="_blank"><?php echo $config_sitemap_manufacturers; ?></a>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_google_analytics; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-analytics"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_google_analytics); ?>"><?php echo $entry_google_analytics; ?></span></label>
                                    <div class="col-sm-10">
                                        <textarea name="config_google_analytics" rows="5" placeholder="<?php echo $entry_google_analytics; ?>" id="input-google-analytics" class="form-control"><?php echo $config_google_analytics; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-analytics-status"><?php echo $entry_google_analytics_status; ?></label>
                                    <!-- <div class="col-sm-10">
                                        <select name="config_google_analytics_status" id="input-google-analytics-status" class="form-control">
                                            <?php if ($config_google_analytics_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_google_analytics_status) { ?>
                                            <input type="radio" name="config_google_analytics_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_google_analytics_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_google_analytics_status) { ?>
                                            <input type="radio" name="config_google_analytics_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_google_analytics_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane" id="tab-cache">
                            <fieldset>
                                <legend><?php echo $text_common; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-cache-storage"><span data-toggle="tooltip" title="<?php echo $help_cache_storage; ?>"><?php echo $entry_cache_storage; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_cache_storage" id="input-cache-storage" class="form-control">
                                            <option value="apc" <?php echo ($config_cache_storage == 'apc') ? 'selected="selected"' : ''; ?> ><?= $text_cache_storage_apc ?></option>
                                            <option value="file" <?php echo ($config_cache_storage == 'file') ? 'selected="selected"' : ''; ?> ><?php echo $text_cache_storage_file; ?></option>
                                            <option value="memcached" <?php echo ($config_cache_storage == 'memcached') ? 'selected="selected"' : ''; ?> >Memcached</option>
                                            <option value="redis" <?php echo ($config_cache_storage == 'redis') ? 'selected="selected"' : ''; ?> >Redis</option>
                                            <option value="wincache" <?php echo ($config_cache_storage == 'wincache') ? 'selected="selected"' : ''; ?> >Wincache</option>
                                            <option value="xcache" <?php echo ($config_cache_storage == 'xcache') ? 'selected="selected"' : ''; ?> >XCache</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-cache-lifetime"><span data-toggle="tooltip" title="<?php echo $help_cache_lifetime; ?>"><?php echo $entry_cache_lifetime; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_cache_lifetime" value="<?php echo $config_cache_lifetime; ?>" placeholder="<?php echo $entry_cache_lifetime; ?>" id="input-cache-lifetime" class="form-control" />
                                        <?php if ($error_cache_lifetime) { ?>
                                        <div class="text-danger"><?php echo $error_cache_lifetime; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_cache_clear; ?>"><?php echo $entry_cache_clear; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_cache_clear" value="1" <?php echo ($config_cache_clear) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_cache_clear" value="0" <?php echo (!$config_cache_clear) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_pagecache; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_pagecache; ?>"><?php echo $entry_pagecache; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_pagecache" value="1" <?php echo ($config_pagecache) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_pagecache" value="0" <?php echo (!$config_pagecache) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-pagecache-exlude"><span data-toggle="tooltip" title="<?php echo $help_pagecache_exclude; ?>"><?php echo $entry_pagecache_exclude; ?></span></label>
                                    <div class="col-sm-10">
                                        <textarea name="config_pagecache_exclude" rows="5" placeholder="<?php echo $entry_pagecache_exclude; ?>" id="input-pagecache-exlude" class="form-control"><?php echo $config_pagecache_exclude; ?></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_cache_clear; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="button-clear"></label>
                                    <div class="col-sm-10">
                                        <button type="button" id="button-clear" class="btn btn-warning"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<?php echo $text_cache_clear; ?></button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane" id="tab-security">
                            <fieldset>
                                <legend><?php echo $text_common; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_secure; ?>"><?php echo $entry_secure; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_secure" id="input-secure" class="form-control">
                                            <option value="0" <?php echo ($config_secure == '0') ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                            <option value="1" <?php echo ($config_secure == '1') ? 'selected="selected"' : ''; ?>><?php echo $text_secure_checkout; ?></option>
                                            <option value="2" <?php echo ($config_secure == '2') ? 'selected="selected"' : ''; ?>><?php echo $text_secure_catalog; ?></option>
                                            <option value="3" <?php echo ($config_secure == '3') ? 'selected="selected"' : ''; ?>><?php echo $text_secure_all; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-encryption"><span data-toggle="tooltip" title="<?php echo $help_encryption; ?>"><?php echo $entry_encryption; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" placeholder="<?php echo $entry_encryption; ?>" id="input-encryption" class="form-control" />
                                        <?php if ($error_encryption) { ?>
                                        <div class="text-danger"><?php echo $error_encryption; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sec-admin-login"><span data-toggle="tooltip" title="<?php echo $help_sec_admin_login; ?>"><?php echo $entry_sec_admin_login; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sec_admin_login" value="<?php echo $config_sec_admin_login; ?>" placeholder="<?php echo $entry_sec_admin_login; ?>" id="input-sec-admin-login" class="form-control" />
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-sec-admin-keyword"><span data-toggle="tooltip" title="<?php echo $help_sec_admin_keyword; ?>"><?php echo $entry_sec_admin_keyword; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_sec_admin_keyword" value="<?php echo $config_sec_admin_keyword; ?>" placeholder="<?php echo $entry_sec_admin_keyword; ?>" id="input-sec-admin-keyword" class="form-control" />
                                    </div>
                                </div> -->
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_firewall; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sec_lfi; ?>"><?php echo $entry_sec_lfi; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_lfi[]" value="get" <?php echo (in_array('get', $config_sec_lfi)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_get; ?>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_lfi[]" value="post" <?php echo (in_array('post', $config_sec_lfi)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_post; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sec_rfi; ?>"><?php echo $entry_sec_rfi; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_rfi[]" value="get" <?php echo (in_array('get', $config_sec_rfi)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_get; ?>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_rfi[]" value="post" <?php echo (in_array('post', $config_sec_rfi)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_post; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sec_sql; ?>"><?php echo $entry_sec_sql; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_sql[]" value="get" <?php echo (in_array('get', $config_sec_sql)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_get; ?>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_sql[]" value="post" <?php echo (in_array('post', $config_sec_sql)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_post; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sec_xss; ?>"><?php echo $entry_sec_xss; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_xss[]" value="get" <?php echo (in_array('get', $config_sec_xss)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_get; ?>
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="config_sec_xss[]" value="post" <?php echo (in_array('post', $config_sec_xss)) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_sec_post; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sec_htmlpurifier; ?>"><?php echo $entry_sec_htmlpurifier; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_sec_htmlpurifier" value="1" <?php echo ($config_sec_htmlpurifier) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_sec_htmlpurifier" value="0" <?php echo (!$config_sec_htmlpurifier) ? 'checked="checked"' : ''; ?>/>
                                                   <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo $text_upload; ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-file-max-size"><span data-toggle="tooltip" title="<?php echo $help_file_max_size; ?>"><?php echo $entry_file_max_size; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_file_max_size" value="<?php echo $config_file_max_size; ?>" placeholder="<?php echo $entry_file_max_size; ?>" id="input-file-max-size" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-file-ext-allowed"><span data-toggle="tooltip" title="<?php echo $help_file_ext_allowed; ?>"><?php echo $entry_file_ext_allowed; ?></span></label>
                                    <div class="col-sm-10">
                                        <textarea name="config_file_ext_allowed" rows="5" placeholder="<?php echo $entry_file_ext_allowed; ?>" id="input-file-ext-allowed" class="form-control"><?php echo $config_file_ext_allowed; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-file-mime-allowed"><span data-toggle="tooltip" title="<?php echo $help_file_mime_allowed; ?>"><?php echo $entry_file_mime_allowed; ?></span></label>
                                    <div class="col-sm-10">
                                        <textarea name="config_file_mime_allowed" cols="60" rows="5" placeholder="<?php echo $entry_file_mime_allowed; ?>" id="input-file-mime-allowed" class="form-control"><?php echo $config_file_mime_allowed; ?></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Google</legend>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-captcha-secret"><?php echo $entry_google_api_key; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_google_api_key" value="<?php echo $config_google_api_key; ?>" placeholder="<?php echo $entry_google_api_key; ?>" id="input-google-api-key" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-captcha-secret"><?php echo $entry_google_server_api_key; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_google_server_api_key" value="<?php echo $config_google_server_api_key; ?>" placeholder="<?php echo $entry_google_server_api_key; ?>" id="input-google-api-key" class="form-control" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-captcha-public"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_google_captcha); ?>"><?php echo $entry_google_captcha_public; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_google_captcha_public" value="<?php echo $config_google_captcha_public; ?>" placeholder="<?php echo $entry_google_captcha_public; ?>" id="input-google-captcha-public" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-captcha-secret"><?php echo $entry_google_captcha_secret; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_google_captcha_secret" value="<?php echo $config_google_captcha_secret; ?>" placeholder="<?php echo $entry_google_captcha_secret; ?>" id="input-google-captcha-secret" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-google-captcha-status"><?php echo $entry_google_recaptch_status; ?></label>
                                    <!-- <div class="col-sm-10">
                                        <select name="config_google_captcha_status" id="input-google-captcha-status" class="form-control">
                                            <?php if ($config_google_captcha_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_google_captcha_status) { ?>
                                            <input type="radio" name="config_google_captcha_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_google_captcha_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_google_captcha_status) { ?>
                                            <input type="radio" name="config_google_captcha_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_google_captcha_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="tab-pane" id="tab-fraud">

                            <fieldset>
                                <legend><?= $text_maxmind ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_fraud_detection); ?>"><?php echo $entry_fraud_detection; ?></span></label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_fraud_detection) { ?>
                                            <input type="radio" name="config_fraud_detection" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_fraud_detection" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_fraud_detection) { ?>
                                            <input type="radio" name="config_fraud_detection" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_fraud_detection" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-fraud-key"><?php echo $entry_fraud_key; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_fraud_key" value="<?php echo $config_fraud_key; ?>" placeholder="<?php echo $entry_fraud_key; ?>" id="input-fraud-key" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-fraud-score"><span data-toggle="tooltip" title="<?php echo $help_fraud_score; ?>"><?php echo $entry_fraud_score; ?></span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_fraud_score" value="<?php echo $config_fraud_score; ?>" placeholder="<?php echo $entry_fraud_score; ?>" id="input-fraud-score" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-fraud-status"><span data-toggle="tooltip" title="<?php echo $help_fraud_status; ?>"><?php echo $entry_fraud_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_fraud_status_id" id="input-fraud-status" class="form-control">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $config_fraud_status_id) { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend><?= $text_konduto ?></legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-konduto-status"><?php echo $entry_konduto_status; ?></label>
                                    <!-- <div class="col-sm-10">
                                        <select name="config_konduto_status" id="input-konduto-status" class="form-control">
                                            <?php if ($config_konduto_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <?php if ($config_konduto_status) { ?>
                                            <input type="radio" name="config_konduto_status" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_konduto_status" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_konduto_status) { ?>
                                            <input type="radio" name="config_konduto_status" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_konduto_status" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_konduto_private_key ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_konduto_private_key" value="<?php echo $config_konduto_private_key; ?>" placeholder="Konduto private key" class="form-control" />
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-name"><?= $entry_konduto_public_key ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="config_konduto_public_key" value="<?php echo $config_konduto_public_key; ?>" placeholder="Konduto public key" class="form-control" />
                                    </div>
                                </div>  
                                

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-fraud-status"><span data-toggle="tooltip" title="<?php echo $help_fraud_status; ?>"><?php echo $entry_konduto_order_status; ?></span></label>
                                    <div class="col-sm-10">
                                        <select name="config_konduto_status_id" id="input-fraud-status" class="form-control">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $config_konduto_status_id) { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                        <div class="tab-pane" id="tab-server">
                            <!-- <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shared; ?>"><?php echo $entry_shared; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_shared) { ?>
                                        <input type="radio" name="config_shared" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_shared" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_shared) { ?>
                                        <input type="radio" name="config_shared" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_shared" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-robots"><span data-toggle="tooltip" title="<?php echo $help_robots; ?>"><?php echo $entry_robots; ?></span></label>
                                <div class="col-sm-10">
                                    <textarea name="config_robots" rows="5" placeholder="<?php echo $entry_robots; ?>" id="input-robots" class="form-control"><?php echo $config_robots; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_maintenance; ?>"><?php echo $entry_maintenance; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_maintenance) { ?>
                                        <input type="radio" name="config_maintenance" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_maintenance" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_maintenance) { ?>
                                        <input type="radio" name="config_maintenance" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_maintenance" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_coming_soon; ?>"><?php echo $entry_coming_soon; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_coming_soon) { ?>
                                        <input type="radio" name="config_coming_soon" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_coming_soon" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_coming_soon) { ?>
                                        <input type="radio" name="config_coming_soon" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_coming_soon" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_password) { ?>
                                        <input type="radio" name="config_password" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_password" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_password) { ?>
                                        <input type="radio" name="config_password" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_password" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-compression"><span data-toggle="tooltip" title="<?php echo $help_compression; ?>"><?php echo $entry_compression; ?></span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_compression" value="<?php echo $config_compression; ?>" placeholder="<?php echo $entry_compression; ?>" id="input-compression" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_debug_system; ?>"><?php echo $entry_debug_system; ?></span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="config_debug_system" value="1" <?php echo ($config_debug_system) ? 'checked="checked"' : ''; ?>/>
                                               <?php echo $text_yes; ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="config_debug_system" value="0" <?php echo (!$config_debug_system) ? 'checked="checked"' : ''; ?>/>
                                               <?php echo $text_no; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_error_display; ?></label>
                                <div class="col-sm-10">
                                    <select name="config_error_display" id="input-secure" class="form-control">
                                        <option value="0" <?php echo ($config_error_display == '0') ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                        <option value="1" <?php echo ($config_error_display == '1') ? 'selected="selected"' : ''; ?>><?php echo $text_error_basic; ?></option>
                                        <option value="2" <?php echo ($config_error_display == '2') ? 'selected="selected"' : ''; ?>><?php echo $text_error_advanced; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_error_log; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($config_error_log) { ?>
                                        <input type="radio" name="config_error_log" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_error_log" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$config_error_log) { ?>
                                        <input type="radio" name="config_error_log" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="config_error_log" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-error-filename"><?php echo $entry_error_filename; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" placeholder="<?php echo $entry_error_filename; ?>" id="input-error-filename" class="form-control" />
                                    <?php if ($error_error_filename) { ?>
                                    <div class="text-danger"><?php echo $error_error_filename; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  <script type="text/javascript"><!--
$('select[name=\'config_template\']').on('change', function() {
    $.ajax({
        url: 'index.php?path=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value),
        dataType: 'html',
        beforeSend: function() {
            $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        complete: function() {
            $('.fa-spin').remove();
        },
        success: function(html) {
            $('#template').attr('src', html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('select[name=\'config_template\']').trigger('change');

/*$('[name=\'config_footer_text\']').on('change', function() {
    console.log("footer change");
    var configMail = $(this).val();
    console.log(configMail);
    if (configMail == '1') {
        $("#input-aboutus").children().removeAttr("disabled");
        $("#input-footer_video_link").prop('disabled',true);
        $("#thumb-footer-image").children().attr("disabled","disabled");
    } else {
        console.log("else");
        $("#input-aboutus").children().attr("disabled","disabled");
        $("#input-footer_video_link").prop('disabled',false);
        //$("#thumb-footer-image").prop('disabled', false);
        $("#thumb-footer-image").children().removeAttr("disabled");
    }   
});*/

//$('[name=\'config_footer_text\']').trigger('change');

$('select[name=\'config_mail[protocol]\']').on('change', function() {
    var configMail = $(this).val();
    
    if (configMail == 'phpmail') {
        $("input[name=\'config_mail[sendmail_path]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_hostname]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_username]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_password]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_port]\']").attr('readonly', true);
        $("select[name=\'config_mail[smtp_encryption]\']").attr('readonly', true);

        $("input[name=\'config_mail[aws_region]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_access_id]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_secret_key]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun_domain]\']").attr('readonly', true);

        $("input[name=\'config_mail[sendmail_path]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_hostname]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_username]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_password]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_port]\']").css('background-color' , '#DEDEDE');
        $("select[name=\'config_mail[smtp_encryption]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_region]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_access_id]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_secret_key]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun_domain]\']").css('background-color' , '#DEDEDE');

    }
    else if(configMail == 'sendmail') {
        
        $("input[name=\'config_mail[smtp_hostname]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_username]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_password]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_port]\']").attr('readonly', true);
        $("select[name=\'config_mail[smtp_encryption]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_region]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_access_id]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_secret_key]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun_domain]\']").attr('readonly', true);
        $("input[name=\'config_mail[sendmail_path]\']").attr('readonly', false);

        
        $("input[name=\'config_mail[smtp_hostname]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_username]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_password]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_port]\']").css('background-color' , '#DEDEDE');
        $("select[name=\'config_mail[smtp_encryption]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_region]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_access_id]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_secret_key]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun_domain]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[sendmail_path]\']").css('background-color' , '');


    }
    else if (configMail == 'smtp') {
        $("input[name=\'config_mail[sendmail_path]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_region]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_access_id]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_secret_key]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun_domain]\']").attr('readonly', true);

        $("input[name=\'config_mail[smtp_hostname]\']").attr('readonly', false);
        $("input[name=\'config_mail[smtp_username]\']").attr('readonly', false);
        $("input[name=\'config_mail[smtp_password]\']").attr('readonly', false);
        $("input[name=\'config_mail[smtp_port]\']").attr('readonly', false);
        $("select[name=\'config_mail[smtp_encryption]\']").attr('readonly', false);

        $("input[name=\'config_mail[smtp_hostname]\']").css('background-color' , '');
        $("input[name=\'config_mail[smtp_username]\']").css('background-color' , '');
        $("input[name=\'config_mail[smtp_password]\']").css('background-color' , '');
        $("input[name=\'config_mail[smtp_port]\']").css('background-color' , '');
        $("select[name=\'config_mail[smtp_encryption]\']").css('background-color' , '');

        $("input[name=\'config_mail[aws_region]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_access_id]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_secret_key]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun_domain]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[sendmail_path]\']").css('background-color' , '#DEDEDE');

    }
    else if (configMail == 'aws') {

        $("input[name=\'config_mail[mailgun]\']").attr('readonly', true);
        $("input[name=\'config_mail[mailgun_domain]\']").attr('readonly', true);
        $("input[name=\'config_mail[sendmail_path]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_hostname]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_username]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_password]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_port]\']").attr('readonly', true);
        $("select[name=\'config_mail[smtp_encryption]\']").attr('readonly', true);

        $("input[name=\'config_mail[aws_region]\']").attr('readonly', false);
        $("input[name=\'config_mail[aws_access_id]\']").attr('readonly', false);
        $("input[name=\'config_mail[aws_secret_key]\']").attr('readonly', false);


        $("input[name=\'config_mail[smtp_hostname]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_username]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_password]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_port]\']").css('background-color' , '#DEDEDE');
        $("select[name=\'config_mail[smtp_encryption]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[sendmail_path]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[mailgun_domain]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_region]\']").css('background-color' , '');
        $("input[name=\'config_mail[aws_access_id]\']").css('background-color' , '');
        $("input[name=\'config_mail[aws_secret_key]\']").css('background-color' , '');
        


        
    }
    else if (configMail == 'mailgun') {
        
        $("input[name=\'config_mail[sendmail_path]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_hostname]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_username]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_password]\']").attr('readonly', true);
        $("input[name=\'config_mail[smtp_port]\']").attr('readonly', true);
        $("select[name=\'config_mail[smtp_encryption]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_region]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_access_id]\']").attr('readonly', true);
        $("input[name=\'config_mail[aws_secret_key]\']").attr('readonly', true);

        $("input[name=\'config_mail[mailgun]\']").attr('readonly', false);
        $("input[name=\'config_mail[mailgun_domain]\']").attr('readonly', false);

        $("input[name=\'config_mail[smtp_hostname]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_username]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_password]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[smtp_port]\']").css('background-color' , '#DEDEDE');
        $("select[name=\'config_mail[smtp_encryption]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[sendmail_path]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_region]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_access_id]\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_mail[aws_secret_key]\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_mail[mailgun]\']").css('background-color' , '');
        $("input[name=\'config_mail[mailgun_domain]\']").css('background-color' , '');
    }


    $('select[name=\'config_mail[smtp_encryption]\']').removeClass('form-control');
    $('select[name=\'config_mail[smtp_encryption]\']').selectpicker('refresh');
});

$('select[name=\'config_mail[protocol]\']').trigger('change');


$('select[name=\'config_sms_protocol\']').on('change', function() {
    var configMail = $(this).val();
    console.log("confirsms");
    console.log(configMail);

    if (configMail == 'twilio') {

        console.log("twilio");
        $("input[name=\'config_zenvia_sms_number\']").attr('readonly', true);
        $("input[name=\'config_zenvia_sms_token\']").attr('readonly', true);
        $("input[name=\'config_zenvia_sms_sender_id\']").attr('readonly', true);

        $("input[name=\'config_wayhub_sms_number\']").attr('readonly', true);
        $("input[name=\'config_wayhub_sms_token\']").attr('readonly', true);
        $("input[name=\'config_wayhub_sms_sender_id\']").attr('readonly', true);


        $("input[name=\'config_sms_sender_id\']").attr('readonly', false);
        $("input[name=\'config_sms_token\']").attr('readonly', false);
        $("input[name=\'config_sms_number\']").attr('readonly', false);


        $("input[name=\'config_zenvia_sms_number\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_zenvia_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_zenvia_sms_sender_id\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_wayhub_sms_number\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_wayhub_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_wayhub_sms_sender_id\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_sms_sender_id\']").css('background-color' , '');
        $("input[name=\'config_sms_token\']").css('background-color' , '');
        $("input[name=\'config_sms_number\']").css('background-color' , '');

        /*uwaziimobile*/        
        $("input[name=\'config_uwaziimobile_sms_token\']").attr('readonly', true);
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").attr('readonly', true);
        $("input[name=\'config_uwaziimobile_sms_number\']").attr('readonly', true);
        

        $("input[name=\'config_uwaziimobile_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_uwaziimobile_sms_number\']").css('background-color' , '#DEDEDE');
        
        /*uwaziimobile end*/        


    } else if(configMail == 'zenvia') {
        console.log("zenvia");
        $("input[name=\'config_sms_sender_id\']").attr('readonly', true);
        $("input[name=\'config_sms_token\']").attr('readonly', true);
        $("input[name=\'config_sms_number\']").attr('readonly', true);

        $("input[name=\'config_zenvia_sms_number\']").attr('readonly', false);
        $("input[name=\'config_zenvia_sms_token\']").attr('readonly', false);
        $("input[name=\'config_zenvia_sms_sender_id\']").attr('readonly', false);

        $("input[name=\'config_wayhub_sms_number\']").attr('readonly', true);
        $("input[name=\'config_wayhub_sms_token\']").attr('readonly', true);
        $("input[name=\'config_wayhub_sms_sender_id\']").attr('readonly', true);

        $("input[name=\'config_sms_sender_id\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_sms_number\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_wayhub_sms_number\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_wayhub_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_wayhub_sms_sender_id\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_zenvia_sms_number\']").css('background-color' , '');
        $("input[name=\'config_zenvia_sms_token\']").css('background-color' , '');
        $("input[name=\'config_zenvia_sms_sender_id\']").css('background-color' , '');

        /*uwaziimobile*/        
        $("input[name=\'config_uwaziimobile_sms_token\']").attr('readonly', true);
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").attr('readonly', true);
        $("input[name=\'config_uwaziimobile_sms_number\']").attr('readonly', true);

        $("input[name=\'config_uwaziimobile_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_uwaziimobile_sms_number\']").css('background-color' , '#DEDEDE');

        
        /*uwaziimobile end*/  


    } else if(configMail == 'uwaziimobile') {
        console.log("uwaziimobile");
        $("input[name=\'config_sms_sender_id\']").attr('readonly', true);
        $("input[name=\'config_sms_token\']").attr('readonly', true);
        $("input[name=\'config_sms_number\']").attr('readonly', true);

        $("input[name=\'config_zenvia_sms_number\']").attr('readonly', true);
        $("input[name=\'config_zenvia_sms_token\']").attr('readonly', true);
        $("input[name=\'config_zenvia_sms_sender_id\']").attr('readonly', true);

        $("input[name=\'config_wayhub_sms_number\']").attr('readonly', true);
        $("input[name=\'config_wayhub_sms_token\']").attr('readonly', true);
        $("input[name=\'config_wayhub_sms_sender_id\']").attr('readonly', true);

        $("input[name=\'config_sms_sender_id\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_sms_number\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_wayhub_sms_number\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_wayhub_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_wayhub_sms_sender_id\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_zenvia_sms_number\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_zenvia_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_zenvia_sms_sender_id\']").css('background-color' , '#DEDEDE');

        /*uwaziimobile*/        
        $("input[name=\'config_uwaziimobile_sms_token\']").attr('readonly', false);
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").attr('readonly', false);
        $("input[name=\'config_uwaziimobile_sms_number\']").attr('readonly', false);

        $("input[name=\'config_uwaziimobile_sms_token\']").css('background-color' , '');
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").css('background-color' , '');
        $("input[name=\'config_uwaziimobile_sms_number\']").css('background-color' , '');

        config_uwaziimobile_sms_number
        /*uwaziimobile end*/  

    } else {
        console.log("else");
        $("input[name=\'config_sms_sender_id\']").attr('readonly', true);
        $("input[name=\'config_sms_token\']").attr('readonly', true);
        $("input[name=\'config_sms_number\']").attr('readonly', true);

        $("input[name=\'config_zenvia_sms_number\']").attr('readonly', true);
        $("input[name=\'config_zenvia_sms_token\']").attr('readonly', true);
        $("input[name=\'config_zenvia_sms_sender_id\']").attr('readonly', true);

        $("input[name=\'config_wayhub_sms_number\']").attr('readonly', false);
        $("input[name=\'config_wayhub_sms_token\']").attr('readonly', false);
        $("input[name=\'config_wayhub_sms_sender_id\']").attr('readonly', false);

        $("input[name=\'config_sms_sender_id\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_sms_number\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_zenvia_sms_number\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_zenvia_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_zenvia_sms_sender_id\']").css('background-color' , '#DEDEDE');

        $("input[name=\'config_wayhub_sms_number\']").css('background-color' , '');
        $("input[name=\'config_wayhub_sms_token\']").css('background-color' , '');
        $("input[name=\'config_wayhub_sms_sender_id\']").css('background-color' , '');

        /*uwaziimobile*/        
        $("input[name=\'config_uwaziimobile_sms_token\']").attr('readonly', true);
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").attr('readonly', true);
        $("input[name=\'config_uwaziimobile_sms_number\']").attr('readonly', true);

        $("input[name=\'config_uwaziimobile_sms_token\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_uwaziimobile_sms_sender_id\']").css('background-color' , '#DEDEDE');
        $("input[name=\'config_uwaziimobile_sms_number\']").css('background-color' , '#DEDEDE');

        
        /*uwaziimobile end*/  


    }
});

//--></script> 

</div>

<script type="text/javascript"><!--
$('#button-clear').on('click', function() {
    $.ajax({
        url: 'index.php?path=setting/setting/clearcache&token=<?php echo $token; ?>',
        dataType: 'json',
        beforeSend: function() {
            $('#button-clear').button('loading');
        },
        success: function(json) {
            $('#button-clear').removeClass('btn-warning');

            if (json['message']) {
                $('#button-clear').addClass('btn-success');
                $('#button-clear').html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;'+json['message']);
            }
            else {
                $('#button-clear').addClass('btn-danger');
                $('#button-clear').html('<i class="fa fa-times-circle"></i>&nbsp;&nbsp;'+json['error']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
//--></script>

<script type="text/javascript"><!--
function save(type){
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
}

function testSms(){
    console.log("testSms");

    

    var to = $('input[name="to"]').val();
    var message = $('textarea[name="message"]').val();

    var jsonArr =  {
        to: to,
        message: message
    };
    console.log(to);
    console.log(message);
    $.ajax({
        url: '<?= $test_sms_link?>&token=<?= $token?>',
        type: 'post',
        data: jsonArr,
        dataType: 'json',
        beforeSend: function() {
            $('#button-send-sms').button('loading');
        },
        complete: function() {
            $('#button-send-sms').button('reset');
        },
        success: function(json) {
            console.log(json);
            
            alert(json.message);
            
        }
    });
    return false;
}

$('#stripe_disconnect').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?path=vendor/vendor/stripeDisconnect&token=<?php echo $token; ?>&vendor_id=0',
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $('#stripe_disconnect').button('loading');
        },
        complete: function() {
            $('#stripe_disconnect').button('reset');
        },
        success: function(response) {
            //$('#stripe_user_id_div').hide();

            if(response['status']) {
                alert('Stripe Account Disconnected!');

                location = location;
            }
        }
    });
});

//--></script>
            
<?php echo $footer; ?>