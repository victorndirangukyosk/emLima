<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-user" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>			
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

        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li ><a href="#tab-general" data-toggle="tab"><?= $tab_general ?></a></li>
                        <li><a href="#tab-commision" data-toggle="tab"><?= $tab_commision ?></a></li>
                        <li class="active"><a href="#tab-contact" data-toggle="tab"><?= $tab_contact ?></a></li>
                        <li><a href="#tab-password" data-toggle="tab"><?= $tab_password ?></a></li>
                        <?php if($vendor_id){ ?>
                        <li><a href="#tab-credit" data-toggle="tab"><?= $tab_wallet ?></a></li>
                        
                        <?php } ?>

                        <?php if (!$this->user->isVendor()) { ?>
                            <li><a href="#tab-connect" data-toggle="tab"> Bank Details</a></li>
                            <li><a href="#tab-store_mapping" data-toggle="tab"> Store Mapping </a></li>
                        <?php } ?>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                                    <?php if ($error_username) { ?>
                                    <div class="text-danger"><?php echo $error_username; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_vendor_group; ?></label>
                                <div class="col-sm-10">
                                    <select name="user_group_id" id="input-user-group" class="form-control">
                                        <?php foreach ($user_groups as $user_group) { ?>
                                        <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                                        <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                                    <?php if ($error_firstname) { ?>
                                    <div class="text-danger"><?php echo $error_firstname; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                                    <?php if ($error_lastname) { ?>
                                    <div class="text-danger"><?php echo $error_lastname; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-free_tin_no"><?php echo $entry_tin_no; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="tin_no" value="<?php echo $tin_no; ?>" placeholder="<?php echo $tin_no; ?>" id="input-tin_no" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-orderprefix"><?php echo $entry_orderprefix; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="orderprefix" value="<?php echo $orderprefix; ?>" placeholder="<?php echo $orderprefix; ?>" id="input-orderprefix" class="form-control" />
                                    <?php if ($error_orderprefix) { ?>
                                    <div class="text-danger"><?php echo $error_orderprefix; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-orderprefix"><?php echo $entry_display_name; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="display_name" value="<?php echo $display_name; ?>" placeholder="<?php echo $display_name; ?>" id="input-display-name" class="form-control" />
                                    <?php if ($error_display_name) { ?>
                                    <div class="text-danger"><?php echo $error_display_name; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-deliverytime"><?php echo $entry_delivery_time; ?></label>
                                <div class="col-sm-10">
                                    <select name="delivery_time" id="input-delivery-time" class="form-control">
                                        <option value="0">Select Delivery Time</option>
                                        <option value="24" <?php if($delivery_time == 24) { ?> selected="selected" <?php } ?> >12</option>
                                        <option value="24" <?php if($delivery_time == 24) { ?> selected="selected" <?php } ?> >24</option>
                                        <option value="48" <?php if($delivery_time == 48) { ?> selected="selected" <?php } ?> >48</option>
                                        <option value="72" <?php if($delivery_time == 72) { ?> selected="selected" <?php } ?> >72</option>
                                    </select>
                                    <?php if ($error_delivery_time) { ?>
                                    <div class="text-danger"><?php echo $error_delivery_time; ?></div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="status" id="input-status" class="form-control">
                                        <?php if ($status) { ?>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <?php } else { ?>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-commision">

                            <!-- <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-commision"><?php echo $entry_commision; ?></label>
                                <div class="col-sm-10">
                                    <input type="number" step=any name="commision" value="<?php echo $commision; ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
                                </div>
                            </div> -->

                            <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-commision"><?php echo $entry_commision; ?> %</label>
                                    <div class="col-sm-2" style="padding-right: 0px;"> 
                                        <input type="number" step=any name="commision" value="<?php echo $commision; ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
                                    </div>
                                    
                                    <center  class="col-sm-1" style="color: green;padding-right: 0px;padding-left: 0px;width: 28px!important;padding-top: 7px;">
                                        + 
                                    </center>

                                    <label class="col-sm-1 control-label" for="input-commision">
                                        <?php echo $entry_fixed_commision; ?>
                                    </label>

                                    <div class="col-sm-2" style="padding-left: 0px;">
                                        <input type="number" step=any name="fixed_commision" value="<?php echo $fixed_commision; ?>" placeholder="<?php echo $entry_fixed_commision; ?>" id="input-commision" class="form-control" />
                                    </div>

                                    <p class="col-sm-1" for="input-commision" style="padding-left: 0px;padding-top: 7px;">
                                        <?php echo $this->currency->getSymbolLeft(); ?>
                                    </p>
                                    
                            </div>

                            <!-- <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-free_from"><?php echo $entry_free_from; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <input type="text" data-date-format="YYYY-MM-DD" name="free_from" value="<?php echo $free_from; ?>" placeholder="<?php echo $entry_free_from; ?>" id="input-free_from" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-free_to"><?php echo $entry_free_to; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <input type="text" data-date-format="YYYY-MM-DD" name="free_to" value="<?php echo $free_to; ?>" placeholder="<?php echo $entry_free_to; ?>" id="input-free_to" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>                                    
                                </div>
                            </div> -->
                            
                        </div>
                        <div id="tab-password" class="tab-pane">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
                                    <?php if ($error_password) { ?>
                                    <div class="text-danger"><?php echo $error_password; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                                <div class="col-sm-10">
                                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
                                    <?php if ($error_confirm) { ?>
                                    <div class="text-danger"><?php echo $error_confirm; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                        </div>             
                        <div id="tab-contact" class="tab-pane active">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                    <?php if ($error_email) { ?>
                                    <div class="text-danger"><?php echo $error_email; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
								<label class="col-sm-2 control-label" for="input-email">Order Notification <?= $entry_email ?></label>
								<div class="col-sm-10">
                                                                        <textarea name="order_notification_emails" rows="5" placeholder="Order Notification Email" id="input-email" class="form-control"><?php echo $order_notification_emails; ?></textarea>
								</div>
			</div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-mobile"><?php echo $entry_mobile; ?></label>
                                <div  class="col-sm-10 input-group" style="left: 14px;">

                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
                                    <input type="text" name="mobile" value="<?php echo $mobile; ?>" placeholder="<?php echo $entry_mobile; ?>" id="input-mobile" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9"/>
                                    
                                    <?php if ($error_mobile) { ?>
                                    <div class="text-danger"><?php echo $error_mobile; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_telephone; ?></label>
                                <!-- <div class="col-sm-10">
                                    
                                </div> -->
                                <div  class="col-sm-10 input-group" style="left: 14px;">

                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
                                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9"/>
                                    <?php if ($error_telephone) { ?>
                                    <div class="text-danger"><?php echo $error_telephone; ?></div>
                                    <?php } ?>
                                </div>

                            </div>        
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_city; ?></label>
                                <div class="col-sm-10">
                                    <select name="city_id" class="form-control">
                                    <?php foreach($cities as $city){ ?>
                                    <?php if($city['city_id'] == $city_id){ ?>
                                    <option selected value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>                                                
                                    <?php }else{ ?>
                                    <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option> 
                                    <?php } ?>
                                    <?php } ?>
                                    </select>
                                    <?php if ($error_city_id) { ?>
                                    <div class="text-danger"><?php echo $error_city; ?></div>
                                    <?php } ?>
                                </div>
                            </div>              
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_address; ?></label>
                                <div class="col-sm-10">
                                    <textarea name="address" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control"><?php echo $address; ?></textarea>
                                    <?php if ($error_address) { ?>
                                    <div class="text-danger"><?php echo $error_address; ?></div>
                                    <?php } ?>
                                </div>


                            </div>

                            <div id="us1" style="width: 100%; height: 400px;"></div>
                            
                            <input type="hidden" name="latitude" value="<?= $latitude ?>" />
                            <input type="hidden" name="longitude" value="<?= $longitude ?>" />
                            
                            <script>
                                $('#us1').locationpicker({
                                    location: {
                                        latitude: <?= $latitude?$latitude:0 ?>,
                                        longitude: <?= $longitude?$longitude:0 ?>
                                    },  
                                    locationName : '<?=  $address; ?>',
                                    radius: 0,
                                    inputBinding: {
                                        latitudeInput: $('input[name="latitude"]'),
                                        longitudeInput: $('input[name="longitude"]'),
                                        locationNameInput: $('#input-address')
                                    },
                                    enableAutocomplete: true
                                });
                            </script>

                        </div>
                        <div class="tab-pane" id="tab-credit">
                            <div id="credit"></div>
                            <br />
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-amount"><?= $entry_order_id ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="order_id" value="" placeholder="<?php echo $entry_order_id; ?>" id="input-order_id" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-credit-description"><?php echo $entry_description; ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-credit-description" class="form-control" />
                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-credit-description"><?php echo $entry_invoice; ?></label>
                                <div class="col-sm-10">
                                  <input type="checkbox" name="has-invoice" class="form-control" /> 
                                </div>
                            </div>                 
                            <div class="text-right">
                                <button type="button" id="button-credit" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_credit_add; ?></button>
                            </div>
                        </div>

                        <!-- tab-store_mapping -->
                        <div class="tab-pane" id="tab-store_mapping">

                                <!-- <input type="hidden" value="<?= $add?>" id="storeoption" style="display: none"> -->

                                <div class="table-responsive">
                                <table id="excel_store_mapping" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left">
                                                Excel Store Name
                                            </td>
                                            <td> Store </td>
                                            <td class="text-left"> Actions </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php $image_row=0; ?>

                                        <?php foreach ($excel_stores as $excel_store) { ?>
                                            <tr id="image-row<?php echo $image_row; ?>">

                                                <td class="text-left">
                                                
                                                  <input name="excel_store_mapping[<?= $excel_store['id'] ?>][text]" value="<?php echo $excel_store['text']; ?>" placeholder="Excel Store Name" />
                                                </td>

                                                <td class="text-right">
                                                    <select name="excel_store_mapping[<?= $excel_store['id'] ?>][store_id]" id="input-status" class="form-control">

                                                        <?php foreach($stores as $store) { ?>
                                                            <?php if ($store['store_id'] == $excel_store['store_id']) { ?>
                                                        
                                                                <option value="<?= $store['store_id'] ?>" selected="selected">
                                                                    
                                                                    <?php echo $store['name']; ?>

                                                                </option>
                                                            <?php } else { ?>
                                                           
                                                                <option value="<?= $store['store_id'] ?>"> <?php echo $store['name']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </td>

                                                <td class="text-left">
                                                    <button type="button" data-id="<?php echo $excel_store['id'] ?>" class="btn btn-danger deleteMap">
                                                        <i class="fa fa-minus-circle"></i>
                                                    </button>
                                               </td>

                                            </tr>
                                        <?php $image_row++; ?>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addImages();" data-toggle="tooltip" title="Add Image" class="btn btn-primary">
                                                     <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- tab-store_mapping end -->

                        <!-- tab-connect -->
                        <div class="tab-pane" id="tab-connect">
                            <div id="connect"></div>

                            <!-- <?php if($stripe_info_exists) { ?>

                                    <div id="stripe_user_id_div">
                                        <center><span> Stripe User Id: <b> <?= $stripe_info['stripe_user_id'] ?> </b></span>
                                        <span> 
                                            <button type="button" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger" id="stripe_disconnect"> Disconnect Account </button>
                                        </span>
                                         </center>
                                    </div>
                                    

                                    
                            <?php } else { ?>
                                <a  onclick="window.open('https://connect.stripe.com/oauth/authorize?response_type=code&client_id=<?= $publishable_key ?>&scope=read_write&state=<?=$vendor_id?>')"  style="cursor: pointer;">
                                    <img src="<?= $stripe_image?>">   
                                </a>
                            <?php } ?> -->

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-bank_account_number">Bank A/c No</label>
                                <div class="col-sm-10">
                                    <input type="bank_account_number" name="bank_account_number" value="<?php echo $bank_account_number; ?>" id="input-bank_account_number" class="form-control" autocomplete="off" />
                                    <?php if ($error_bank_account_number) { ?>
                                    <div class="text-danger"><?php echo $error_bank_account_number; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-bank_account_name">Bank Account Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>"  id="input-bank_account_name" class="form-control" />
                                    <?php if ($error_bank_account_name) { ?>
                                    <div class="text-danger"><?php echo $error_bank_account_name; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-bank_name">Bank Name</label>
                                <div class="col-sm-10">
                                    <input type="bank_name" name="bank_name" value="<?php echo $bank_name; ?>"  id="input-bank_name" class="form-control" autocomplete="off" />
                                    <?php if ($error_bank_name) { ?>
                                    <div class="text-danger"><?php echo $error_bank_name; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-bank_branch_name">Branch Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="bank_branch_name" value="<?php echo $bank_branch_name; ?>"  id="input-bank_branch_name" class="form-control" />
                                    <?php if ($error_bank_branch_name) { ?>
                                    <div class="text-danger"><?php echo $error_bank_branch_name; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-user-group">Account Type</label>
                                <div class="col-sm-10">
                                    <select name="bank_account_type" id="input-user-group" class="form-control">
                                        
                                        <?php if ($bank_account_type == 'USD') { ?>
                                        <option value="Ksh" >KSh</option>
                                        <option value="USD" selected="selected"> USD </option>
                                        <?php } else { ?>
                                            <option value="Ksh" selected="selected">KSh</option>
                                            <option value="USD"> USD </option>
                                        <?php } ?>
                                        
                                    </select>
                                </div>
                            </div>

                            
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript">
$('.date').datetimepicker({
    pickTime: false
});
</script>

<script type="text/javascript">
    <!--
    var image_row = 1;

    //console.log($image_row);

    function addImages() {
        console.log("addImages");

        addhtml = '';

        <?php foreach($data['stores'] as $store): ?>
            addhtml += '<option value="'+ "<?php echo $store['store_id']?>" +'">'+ "<?php echo $store['name']?>" +'</option>';
        <?php endforeach; ?>

        console.log(addhtml);
        
        
        html = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><input type="text" name="excel_store[' + image_row + '][text]" value="" id="input-image' + image_row + '" /></td>';


        
                                                    

        html += '  <td class="text-right"> <select name="excel_store[' + image_row + '][store_id]" id="input-status" class="form-control"> '+addhtml+'  </select> </td>';


        html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#excel_store_mapping tbody').append(html);

        image_row++;
    }
    //-->
</script>

<script type="text/javascript"><!--
$('#excel_store_mapping').delegate('.deleteMap', 'click', function() {
    console.log('remove');
    $(this).closest('tr').remove();
    var data = {
        id :$(this).data('id'),
        token:'<?php echo $token; ?>'
    };

    $.ajax({
        url:'index.php?path=vendor/vendor/deleteExcelStoreMapping',
        data:data,
        success:function(data){
            
        }
    });
});

<?php if($vendor_id) { ?>



$('#credit').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#credit').load(this.href);
});

$('#credit').load('index.php?path=vendor/vendor/credit&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>');

$('#button-credit').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?path=vendor/vendor/credit&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>',
        type: 'post',
        dataType: 'html',
        data: 'description=' + encodeURIComponent($('#tab-credit input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-credit input[name=\'amount\']').val())+ '&order_id=' + encodeURIComponent($('#tab-credit input[name=\'order_id\']').val())+ '&iugu-transfer=' + encodeURIComponent($('#tab-credit input[name=\'iugu-transfer\']').is(':checked'))+ '&has-invoice=' + encodeURIComponent($('#tab-credit input[name=\'has-invoice\']').is(':checked')),
        beforeSend: function() {
            $('#button-credit').button('loading');
        },
        complete: function() {
            $('#button-credit').button('reset');
        },
        success: function(html) {
            $('.alert').remove();

            $('#credit').html(html);

            $('#tab-credit input[name=\'order_id\']').val('');
            $('#tab-credit input[name=\'amount\']').val('');
            $('#tab-credit input[name=\'description\']').val('');
        }
    });
});

$('#stripe_disconnect').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?path=vendor/vendor/stripeDisconnect&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>',
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


<?php } ?>

$('input[name=\'state\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?path=localisation/zone/autocomplete&token=<?php echo $token; ?>&filter_zone_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['zone_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'state\']').val(item['label']);
	}
});

function save(type){
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}

$('#credit').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();
	$('#credit').load(this.href);
});

$('#credit').load('index.php?path=vendor/vendor/credit&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>');

</script>
<?php echo $footer; ?> 