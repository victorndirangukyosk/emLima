<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-customer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
    <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
    <button type="submit" onclick="save('new')" form="form-customer" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($customer_id) { ?>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <li><a href="#tab-credit" data-toggle="tab"><?php echo $tab_credit; ?></a></li>
           <!-- <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>-->
            <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
            <li><a href="#tab-referral" data-toggle="tab"><?php echo $tab_referral; ?></a></li>
	    <li><a href="#tab-sub-customer" data-toggle="tab"><?php echo $tab_sub_customer; ?></a></li>
            <li><a href="#tab-contact" data-toggle="tab"><?php echo $tab_contact; ?></a></li>
            <li><a href="#tab-configuration" data-toggle="tab">Configuration</a></li>
            <li><a href="#tab-otp" data-toggle="tab">OTP</a></li>
            <li><a href="#tab-activity" data-toggle="tab">Activities</a></li>
            <li><a href="#tab-password" data-toggle="tab">Password</a></li>
           
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="address">
                    <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <li><a href="#tab-address<?php echo $address_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('#address a:first').tab('show'); $('#address a[href=\'#tab-address<?php echo $address_row; ?>\']').parent().remove(); $('#tab-address<?php echo $address_row; ?>').remove();"></i> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
                    <?php $address_row++; ?>
                    <?php } ?>
                    <li id="address-add"><a onclick="addAddress();"><i class="fa fa-plus-circle"></i> <?php echo $button_address_add; ?></a></li>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                        <input type="hidden" id="selected_address" name="selected_address" value="">

                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                        <div class="col-sm-10">
                          <select name="customer_group_id" id="input-customer-group" class="form-control">
                            <?php foreach ($customer_groups as $customer_group) { ?>
                            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
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
                      
                       <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-date-added"><?php echo $entry_dob; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="<?php echo $entry_dob; ?>" id="input-date-added" class="form-control date_dob" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span>
                        </div>
                      </div> 
                        
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname">National ID</label>
                        <div class="col-sm-10">
                          <input type="text" name="national_id" value="<?php echo $national_id; ?>" placeholder="National ID" id="input-national_id" class="form-control" maxlength=10 />
                          <?php if ($error_national_id) { ?>
                          <div class="text-danger"><?php echo $error_national_id; ?></div>
                          <?php } ?>
                        </div>
                      </div>  
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                          <?php if ($error_email) { ?>
                          <div class="text-danger"><?php echo $error_email; ?></div>
                          <?php  } ?>
                        </div>
                      </div>

                      <!-- start -->
                      
                      <?php /* if($customer_group_id != $this->config->get('config_customer_group_id')) { */?>
                          <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-company_name"><?php echo $entry_company_name; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="company_name" value="<?php echo $company_name; ?>" placeholder="<?php echo $entry_company_name; ?>" id="input-company_name" class="form-control" />
                               <?php if ($error_company_name) { ?>
                              <div class="text-danger"><?php echo $error_company_name; ?></div>
                              <?php  } ?> 
                            </div>
                          </div>

                          <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-company_address"><?php echo $entry_company_address; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="company_address" value="<?php echo $company_address; ?>" placeholder="<?php echo $entry_company_address; ?>" id="input-company_address" class="form-control" />
                               <?php if ($error_company_address) { ?>
                              <div class="text-danger"><?php echo $error_company_address; ?></div>
                              <?php  } ?>  
                            </div>
                          </div>
                      <?php /* } */ ?>
                      

                      <!-- end -->

                      <!-- <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-date-added"><?php echo $entry_dob; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="dob" value="<?php echo $dob; ?>" placeholder="<?php echo $entry_dob; ?>" id="input-date-added" class="form-control date_dob" />
                           // <span class="input-group-btn">
                              //  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                           // </span>
                        </div>
                      </div>  -->

                    <!--<div class="form-group">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_gender; ?></label>
                        <div class="col-sm-10">
                           
                            <?php if($gender == 'male') {?> 
                                <input type="radio" name="sex" data-id="8" value="male" checked="checked" /> <?= $text_male ?> 
                            <?php } else {?>
                            <input type="radio" name="sex" data-id="8" value="male" checked="checked"/> <?= $text_male ?> 
                            <?php } ?>
                         

                          
                            <?php if($gender == 'female') {?> 
                                <input type="radio" name="sex" data-id="9" value="female" checked="checked"/> <?= $text_female ?>
                            <?php } else {?>
                            <input type="radio" name="sex" data-id="9" value="female"/> <?= $text_female ?> 
                            <?php } ?>
                           

                          
                            <?php if($gender == 'other') {?> 
                                <input type="radio" name="sex" data-id="10" value="other" checked="checked"/> <?= $text_other ?>
                            <?php } else {?>
                            <input type="radio" name="sex" data-id="10" value="other"/> <?= $text_other ?> 
                            <?php } ?>
                           
                        </div>
                      </div>-->
 

 

                      

                
                      

                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" maxlength=10 onkeypress="return isNumberKey(event)"  />
                          <?php if ($error_telephone) { ?>
                          <div class="text-danger"><?php echo $error_telephone; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                        </div>
                      </div>                        
                    <!--<div class="form-group required">
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
                          <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                          <?php if ($error_confirm) { ?>
                          <div class="text-danger"><?php echo $error_confirm; ?></div>
                          <?php  } ?>
                        </div>
                      </div>-->
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-newsletter"><?php echo $entry_newsletter; ?></label>
                        <div class="col-sm-10">
                          <select name="newsletter" id="input-newsletter" class="form-control">
                            <?php if ($newsletter) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                          <select name="status" id="input-status" class="form-control">
                            <?php if ($status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-approved"><?php echo $entry_approved; ?></label>
                        <div class="col-sm-10">
                          <select name="approved" id="input-approved" class="form-control">
                            <?php if ($approved) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-safe"><?php echo $entry_safe; ?></label>
                        <div class="col-sm-10">
                          <select name="safe" id="input-safe" class="form-control">
                            <?php if ($safe) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                                                  
                            <?php if($parent_user_name != NULL) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-parent-user">Parent User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $parent_user_name; ?>" readonly="" class="form-control" />
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($parent_user_email != NULL) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-parent-user">Parent User Email</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $parent_user_email; ?>" readonly="" class="form-control" />
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($parent_user_phone != NULL) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-parent-user">Parent User Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $parent_user_phone; ?>" readonly="" class="form-control" />
                                </div>
                            </div>
                            <?php } ?>
                            
                            <!--<?php if($account_manager_name != NULL) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-account-manager">Account Manager Name</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $account_manager_name; ?>" readonly="" class="form-control" />
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($account_manager_email != NULL) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-account-manager">Account Manager Email</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $account_manager_email; ?>" readonly="" class="form-control" />
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($account_manager_phone != NULL) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-account-manager">Account Manager Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $account_manager_phone; ?>" readonly="" class="form-control" />
                                </div>
                            </div>
                            <?php } ?>-->
                        <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-source">Source</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $source; ?>" readonly="" class="form-control" />
                                </div>
                        </div>
                           <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-SAP_customer_no">SAP Customer Number</label>
                                <div class="col-sm-10">
                                    <input type="text" maxlength=30  name="SAP_customer_no" value="<?php echo $SAP_customer_no; ?>"  placeholder="SAP Custumer Number"  id="input-SAP_customer_no" class="form-control" />
                                </div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-apply-pezesha">Pezesha</label>
                                <div class="col-sm-10"><button id="button-pezesha" class="btn btn-success"><i class="fa fa-cog"></i>Apply For Pezesha</button></div>
                        </div>    

                      <?php if(count($referee) > 0) { ?>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_referred_by; ?></label>
                            <div class="col-sm-10">
                              <a href="<?php echo $referee_link ?>"> <?php echo $referee['firstname']. ' ' .$referee['lastname']; ?> </a>
                            </div>
                          </div>
                      <?php } ?>

                      <?php if(!empty($show_send_email)) { ?>  
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-send-email"><?php echo $entry_send_email; ?></label>
                        <div class="col-sm-10">
                          <select name="send_email" id="input-send-email" class="form-control">
                            <?php if ($send_email) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <?php } ?>  
                    </div>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
                      <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
      
     <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-address-type<?php echo $address_row; ?>">Address Type</label>
                <div class="col-sm-10">
                <select name="address[<?php echo $address_row; ?>][address_type]" class="form-control">
                <option <?php if($address['address_type'] == 'home') { ?> selected="" <?php } ?> value="home">Home</option>
                <option <?php if($address['address_type'] == 'office') { ?> selected="" <?php } ?> value="office">Office</option>
                <option <?php if($address['address_type'] == 'other' || $address['address_type'] == NULL) { ?> selected="" <?php } ?> value="other">Other</option>       
                </select>
                </div>
    </div>                 


                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-name<?php echo $address_row; ?>">Name</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][name]" value="<?php echo $address['name']; ?>" placeholder="Name" id="input-name<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['name'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['name']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                            <label class="col-sm-2 control-label" for="flat"><?= $text_flat_house_office?></label>
                            <div class="col-md-10">
                                <input type="text" name="address[<?php echo $address_row; ?>][flat_number]" value="<?php echo $address['flat_number']; ?>" placeholder="flat_number" id="input-flat_number<?php echo $address_row; ?>" class="form-control" />
                                  <?php if (isset($error_address[$address_row]['flat_number'])) { ?>
                                  <div class="text-danger"><?php echo $error_address[$address_row]['flat_number']; ?></div>
                                  <?php } ?>
                            </div>
                        </div>
                        <!-- Text input-->
                      <!--   <div class="form-group">
                            <label class="col-sm-2 control-label" for="street"><?= $text_stree_society_office?></label>
                            <div class="col-md-10">
                                <input type="text" name="address[<?php echo $address_row; ?>][building_name]" value="<?php echo $address['building_name']; ?>" placeholder="building_name" id="input-building_name<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['building_name'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['building_name']; ?></div>
                          <?php } ?>
                            </div>
                        </div>-->
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="Locality"><?= $text_locality?></label>
                            <div class="col-md-10">
                                <input type="text" name="address[<?php echo $address_row; ?>][landmark]" value="<?php echo $address['landmark']; ?>" placeholder="" id="input-landmark<?php echo $address_row; ?>" class="form-control" />
                                <input type="hidden" name="address[<?php echo $address_row; ?>][city_id]" value="<?php echo $address['city_id']; ?>" placeholder="" id="input-city-id<?php echo $address_row; ?>" class="form-control" />
                                <input type="hidden" name="address[<?php echo $address_row; ?>][latitude]" value="<?php echo $address['latitude']; ?>" placeholder="" id="input-latitude<?php echo $address_row; ?>" class="form-control" />
                                <input type="hidden" name="address[<?php echo $address_row; ?>][longitude]" value="<?php echo $address['longitude']; ?>" placeholder="" id="input-longitude<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['landmark'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['landmark']; ?></div>
                          <?php } ?>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label" for="zipcode"><?= $label_zipcode ?></label>
                            <div class="col-md-10">-->
                                <!-- <input type="hidden" name="address[<?php echo $address_row; ?>][zipcode]" value="<?php echo $address['zipcode']; ?>" id="input-zipcode<?php echo $address_row; ?>" class="form-control" disabled/> -->
                               <!--  <input type="text" name="address[<?php echo $address_row; ?>][zipcode]" value="<?php echo $address['zipcode']; ?>" placeholder="zipcode" id="input-zipcode<?php echo $address_row; ?>" class="form-control"/>
                          <?php if (isset($error_address[$address_row]['zipcode'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['zipcode']; ?></div>
                          <?php } ?>
                            </div>
                        </div>-->
                      <!-- <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-1<?php echo $address_row; ?>"><?= $entry_contact_no ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][contact_no]" value="<?php echo $address['contact_no']; ?>" placeholder="Contact no" id="input-address-1<?php echo $address_row; ?>" class="form-control"/>
                          <?php if (isset($error_address[$address_row]['contact_no'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['contact_no']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-2<?php echo $address_row; ?>"><?= $entry_address ?></label>
                        <div class="col-sm-10">
                            <textarea name="address[<?php echo $address_row; ?>][address]" placeholder="Address" id="input-address-2<?php echo $address_row; ?>" class="form-control"><?php echo $address['address']; ?></textarea>
                            <?php if (isset($error_address[$address_row]['address'])) { ?>
                              <div class="text-danger"><?php echo $error_address[$address_row]['address']; ?></div>
                            <?php } ?>
                        </div>
                      </div>-->
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-city<?php echo $address_row; ?>"><?php echo $entry_city; ?></label>
                        <div class="col-sm-10">
                            <select name="address[<?php echo $address_row; ?>][city_id]" class="form-control">
                                <option value="">City</option>
                               
                                <?php foreach($cities as $city){ ?>
                                <?php if($city['city_id'] == $address['city_id']){ ?>
                                <option selected="" value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                                <?php }else{ ?>
                                <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select> 
                            <?php if (isset($error_address[$address_row]['city_id'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['city_id']; ?></div>
                          <?php } ?>

                        </div>
                      </div> 
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>
                        <div class="col-sm-10">
                          <label class="radio">
                            <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                            <input type="radio" group="default" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="radio" group="default" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
                            <?php } ?>
                          </label>
                        </div>
                      </div>
                    </div>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <?php if ($customer_id) { ?>
            <div class="tab-pane" id="tab-history">
              <div id="history"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                <div class="col-sm-10">
                  <textarea name="comment" rows="8" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"></textarea>
                </div>
              </div>
              <div class="text-right">
                <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
              </div>
            </div>
            <div class="tab-pane" id="tab-credit">
              <div id="credit"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-credit-description"><?php echo $entry_description; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-credit-description" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
                <div class="col-sm-10">
                  <input type="number" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="button-credit" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_credit_add; ?></button>
              </div>
            </div>
            <div class="tab-pane" id="tab-reward">
              <div id="reward"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-reward-description"><?php echo $entry_description; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-reward-description" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="points" value="" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="button-reward" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
              </div>
            </div>
            


              <div class="tab-pane" id="tab-contact">
              <div id="contact"></div>
              <br />
              <div class="form-group required">
                 <label for="input-contactpersonfirstname" class="col-sm-3 control-label">Contact Person First Name</label>
                                    <div class="col-sm-6">
                                        <input type="text"  value=""  hidden name="contactid" maxlength="100" id="input-contactid"   />

                                        <input type="text"  value=""  size="30" placeholder="Contact Person First Name" name="contactpersonfirstname" maxlength="100" id="input-contactpersonfirstname" class="form-control input-lg" />
                                        <?php if($error_contactpersonfirstname) { ?>
                                        <div class="text-danger"><?php echo $error_contactpersonfirstname; ?></div>
                                        <?php } ?>
                                    </div> 
              </div>
              <div class="form-group">
                 <label class="col-sm-3 control-label" for="input-contactpersonlastname">Contact Person Last Name</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="text" name="contactpersonlastname" value="" placeholder="Contact Person Last Name" id="input-contactpersonlastname" class="form-control input-lg" />
                                        <?php if($error_contactpersonlastname) { ?>
                                        <div class="text-danger"><?php echo $error_contactpersonlastname; ?></div>
                                        <?php } ?>
                                    </div>

              </div>



               <div class="form-group required">
                                    <label class="col-sm-3 control-label" for="input-contactpersonemail">Contact Person Email</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <input type="email" name="contactpersonemail"  value="" placeholder="Contact Person Email"  id="input-contactpersonemail"  class="form-control input-lg" />
                                        <?php if($error_contactpersonemail) { ?>
                                        <div class="text-danger"><?php echo $error_contactpersonemail; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>




                                   <div class="form-group">
                                    <label class="col-sm-3 control-label" for="input-contactpersontelephone">Phone</label>

                                    <div class="col-sm-6 col-xs-12" style="padding-right: 15px;padding-left: 15px;">
 

                                        <input type="tel" name="contactpersontelephone"  value=""  id="input-contactpersontelephone" placeholder="Phone" class="form-control input-lg" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 & amp;
                                                & amp;
                                                event.charCode <= 57" minlength="9" maxlength="9" />

                                        <?php if ($error_contactpersontelephone) { ?>
                                        <div class="text-danger"><?php echo $error_contactpersontelephone; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
     
                               <div class="form-group required">
                                  <label class="col-sm-3 control-label" for="input-contactsend">Send Invoice</label>
                                   </br><div class="col-sm-6 col-xs-12">
                                 
                                        <input type="checkbox" checked   id="customer_contact_send" name="customer_contact_send">
                                             
                                        </input>
                                    </div> 
                               </div>
                               
                                  

                                  
              <div class="text-right">
                <button type="button" id="button-contact" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_contact_add; ?></button>
              </div>
            </div>
            

            <div class="tab-pane" id="tab-password">                  
                    
                     

                       <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-password-1"><?php echo $entry_password; ?></label>
                        <div class="col-sm-10">
                          <input type="password" name="password-1" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password-1" class="form-control" autocomplete="off" />
                          <?php if ($error_password) { ?>
                          <div class="text-danger"><?php echo $error_password; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-confirm-1"><?php echo $entry_confirm; ?></label>
                        <div class="col-sm-10">
                          <input type="password" name="confirm-1" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm-1" class="form-control" />
                          <?php if ($error_confirm) { ?>
                          <div class="text-danger"><?php echo $error_confirm; ?></div>
                          <?php  } ?>
                        </div>
                      </div>

                    
                                    
                         
                  <div class="text-right">
                      <button type="button" id="button-password" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save Password </button>
                  </div>                
            </div>


            <div class="tab-pane" id="tab-configuration">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-price-category">Price Category</label>
                        <div class="col-sm-10">
                            <select name="customer_category" id="input-price-category" class="form-control" <?php echo $customer_category_disabled; ?> >
                            <option value="">Select Category</option>
                            <?php foreach ($price_categories as $category) { ?>
                            <?php if(isset($customer_category) && ($customer_category== $category['price_category'])){?>
                            <option selected="selected" value="<?php echo $category['price_category']; ?>"><?php echo $category['price_category']; ?></option>
                            <?php }else {?>
                             <option  value="<?php echo $category['price_category']; ?>"><?php echo $category['price_category']; ?></option>
                             <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-account-manager">Account Manager Name</label>
                        <div class="col-sm-10">
                            <select name="account_manager" id="input-account-manager" class="form-control" <?php echo $customer_category_disabled; ?> >
                            <option value="">Select Account Manager</option>
                            <?php foreach ($account_managers_list as $account_managers_lis) { ?>
                             <?php if(isset($account_manager) && ($account_manager == $account_managers_lis['user_id'])){ ?>
                            <option selected="selected" value="<?php echo $account_managers_lis['user_id']; ?>"><?php echo $account_managers_lis['firstname'].''.$account_managers_lis['lastname']; ?></option>
                            <?php } else { ?>
                             <option  value="<?php echo $account_managers_lis['user_id']; ?>"><?php echo $account_managers_lis['firstname'].''.$account_managers_lis['lastname']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-account-manager">Customer Experience</label>
                        <div class="col-sm-10">
                            <select name="customer_experience" id="input-customer-experience" class="form-control">
                            <option value="">Select Customer Experience</option>
                            <?php foreach ($customer_experience_list as $customer_experience_lis) { ?>
                             <?php if(isset($customer_experience) && ($customer_experience == $customer_experience_lis['user_id'])){ ?>
                            <option selected="selected" value="<?php echo $customer_experience_lis['user_id']; ?>"><?php echo $customer_experience_lis['firstname'].''.$customer_experience_lis['lastname']; ?></option>
                            <?php } else { ?>
                             <option  value="<?php echo $customer_experience_lis['user_id']; ?>"><?php echo $customer_experience_lis['firstname'].''.$customer_experience_lis['lastname']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                    </div>
                            
                    <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-payment-terms">Payment Terms</label>
                        <div class="col-sm-10">
                            <select name="payment_terms" id="input-payment-terms" class="form-control">
                            <option value="">Payment Terms</option>
                            <option <?php if($payment_terms == "Payment On Delivery") { ?> selected="selected" <?php } ?> value="Payment On Delivery">Payment On Delivery</option>
                            <option <?php if($payment_terms == "7 Days Credit") { ?>  selected="selected" <?php } ?> value="7 Days Credit">7 Days Credit</option>
                            <option <?php if($payment_terms == "15 Days Credit") { ?>  selected="selected" <?php } ?> value="15 Days Credit">15 Days Credit</option>
                            <option <?php if($payment_terms == "30 Days Credit") { ?>  selected="selected" <?php } ?> value="30 Days Credit">30 Days Credit</option>
                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-statement_duration">Statement Duration</label>
                        <div class="col-sm-10">
                            <select name="statement_duration" id="input-statement_duration" class="form-control">
                            
                            <option <?php if($statement_duration == "7") { ?> selected="selected" <?php } ?> value="7">Weekly</option>
                            <option <?php if($statement_duration == "15") { ?>  selected="selected" <?php } ?> value="15">Bi-Weekly</option>
                            <option <?php if($statement_duration == "30") { ?>  selected="selected" <?php } ?> value="30">Monthly</option>
                            </select>

                                
                        </div>
                    </div>        
            <div class="text-right">

             <?php if ($this->user->hasPermission('modify', 'sale/customer_configuration')) {?>
                <button type="button" id="button-configuration" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save Configuration </button>
             <?php }  else {?>
                <button disabled type="button"  data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-save"></i> Save Configuration </button>
             <?php } ?>

            </div>                
            </div>

               <div class="tab-pane" id="tab-activity">
                <div id="activity"></div>
                <br />
            </div>

            
            <div class="tab-pane" id="tab-otp">
              <table class="table table-bordered">
            <thead>
            <tr>
              <th>Customer Name </th>
              <th>OTP</th>
              <th>Type</th>
              <th>Created At</th>
              <th>Updated At</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($customer_otp_list)) { ?>
            <?php foreach($customer_otp_list as $otp) { ?>
            <tr>
            <td><?php echo $firstname.' '.$lastname; ?></td>
            <td><?php echo $otp['otp'];?></td>
            <td><?php echo $otp['type'];?></td>
            <td><?php echo $otp['created_at'];?></td>
            <td><?php echo $otp['updated_at']; ?></td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php if(count($customer_otp_list_phone)) { ?>
            <?php foreach($customer_otp_list_phone as $otp_phone) { ?>
            <tr>
            <td><?php echo $firstname.' '.$lastname; ?></td>
            <td><?php echo $otp_phone['otp'];?></td>
            <td><?php echo $otp_phone['type'];?></td>
            <td><?php echo $otp_phone['created_at'];?></td>
            <td><?php echo $otp_phone['updated_at']; ?></td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php if(count($customer_otp_list) == 0 && count($customer_otp_list_phone) == 0) { ?>
            <tr style="text-align:center">
              <td colspan="5">No OTP Found</td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
            </div> 
                            
            <div class="tab-pane" id="tab-ip">
              <div id="ip"></div>
              <br />
            </div>

            <div class="tab-pane" id="tab-referral">
              <div id="referral"></div>
              <br />
            </div>
            <div class="tab-pane" id="tab-referral">
              <div id="referral"></div>
              <br />
            </div>
            <?php } ?>
            <div class="tab-pane" id="tab-sub-customer">
              <table class="table table-bordered">
            <thead>
            <tr>
              <th>Customer Name </th>
              <th>E-Mail</th>
              <th>Phone No</th>
              <th>Customer Group</th>
              <th>Status</th>
              <!--<th>Action</th>-->
            </tr>
            </thead>
            <tbody>
            <?php if(count($sub_users)){?>
            <?php foreach($sub_users as $user){?>
            <tr>
            <td><?php echo $user['firstname'].' '.$user['lastname'];?></td>
            <td><?php echo $user['email'];?></td>
            <td><?php echo $user['telephone'];?></td>
            <td><?php echo $user['customer_group'];?></td>
            <td><?php echo ($user['approved']==0) ? 'Unverified': 'Verified'?></td>
            <!--<td>Action</td>-->
            </tr>
            <?php } ?>
            <?php }else{ ?>
            <tr style="text-align:center">
              <td colspan="5">No User found</td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script type="text/javascript"><!--
      
$('#tab-general input[type="radio"]').click(function(){
    $('#tab-general input[type="radio"]').prop('checked', false);
    $(this).prop('checked', true);
});
      
$('select[name=\'customer_group_id\']').on('change', function() {
  $.ajax({
    url: 'index.php?path=sale/customer/customfield&token=<?php echo $token; ?>&customer_group_id=' + this.value,
    dataType: 'json',
    success: function(json) {
      $('.custom-field').hide();
      $('.custom-field').removeClass('required');

      for (i = 0; i < json.length; i++) {
        custom_field = json[i];

        $('.custom-field' + custom_field['custom_field_id']).show();

        if (custom_field['required']) {
          $('.custom-field' + custom_field['custom_field_id']).addClass('required');
        }
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('select[name=\'customer_group_id\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {
  
  html  = '<div class="tab-pane" id="tab-address' + address_row + '">';
  html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';

  html += '  <div class="form-group required">';
  html += '  <label class="col-sm-2 control-label" for="input-address-type'+address_row+'">Address Type</label>';
  html += '  <div class="col-sm-10">';
  html += '  <select name="address['+address_row+'][address_type]" class="form-control">';
  html += '  <option value="home">Home</option>';
  html += '  <option value="office">Office</option>';
  html += '  <option value="other">Other</option>';
  html += '  </select>';
  html += '  </div>';
  html += ' </div>';
  html += '<input type="hidden" name="address['+address_row+'][city_id]" value="32" placeholder="" id="input-city-id<?php echo $address_row; ?>" class="form-control" />';
  html += '<input type="hidden" name="address['+address_row+'][latitude]" value="" placeholder="" id="input-latitude'+address_row+'" class="form-control" />';
  html += '<input type="hidden" name="address['+address_row+'][longitude]" value="" placeholder="" id="input-longitude'+address_row+'" class="form-control" />';



  html += '  <div class="form-group required">';
  html += '    <label class="col-sm-2 control-label" for="input-name' + address_row + '">Name</label>';
  html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][name]" value="" placeholder="Name" id="input-name' + address_row + '" class="form-control" /></div>';
  html += '  </div>';


  html += '  <input id="street' + address_row + '" name="address[' + address_row + '][street]" type="hidden"  class="form-control input-md" required="">';
  html += '   <input id="picker_city_name' + address_row + '" name="address[' + address_row + '][picker_city_name]" type="hidden" value="">';

    html += '  <div class="form-group required">';
    html += '    <label class="col-sm-2 control-label" for="input-flat_number' + address_row + '">House No. and Building Name</label>';
    html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][flat_number]" value="" placeholder="45,Sunshine Apartments" id="input-flat_number' + address_row + '" class="form-control" /></div>';
    html += '  </div>';



     <!-- Text input--> 

  html += '  <?php if($this->config->get('config_store_location') == 'zipcode') { ?>';
  html += ' <div class="form-group required">';
  html += '   <label class="col-sm-2 control-label" for="input-Locality' + address_row + '"> Your Location</label>';

  html += '  <?php if($check_address) { ?>'; 
  html += '  <div class="col-sm-10">';

  html += ' <input name="modal_address_locality" type="text" value=""  class="form-control input-md LocalityId" id="address[' + address_row + '][address_locality]" required=""   >     ';                                               
  html += ' <span class="form-group-btn">';

  html += ' <button id="locateme" data-address-id="'+address_row+'" class="btn btn-default disabled" style=" color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap('+address_row+')" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> Locate Me </button>';

  html += ' </span>';
  html += '  </div>';

  html += ' <?php } else { ?>';
  html += ' <div class="col-sm-10">';
  html += ' <input  name="address[' + address_row + '][address_locality]" type="text"  class="form-control input-md LocalityId" id="address[' + address_row + '][address_locality]"  required=""  >';
  html += ' </div>';
  html += ' <?php } ?>';
  html += ' </div>';

  html += ' <?php } else { ?>';
                                        
  html += ' <div class="form-group required">';
  html += ' <label class="col-sm-2 control-label" for="input-Locality' + address_row + '">Your Location</label>';

  html += ' <?php if(1==1) { ?>';
  <!-->$check_address-->

  html += ' <div class="col-sm-10">';
  html += ' <input  name="address[' + address_row + '][landmark]" type="text"  class="form-control input-md LocalityId" id="input-address_locality' + address_row + '" required=""  class="form-control">';                                                    
  html += ' <span class="input-group-btn">';

  html += ' <button id="locateme" data-address-id="'+address_row+'" class="btn btn-default" style="height:38px;color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap('+address_row+')" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> Locate Me </button>';

  html += ' </span>';
  
  html += '  </div>';
  html += ' <?php } else { ?>';
  html += ' <div class="col-sm-10">';
  html += ' <input  name="address[' + address_row + '][landmark]" type="text"  class="form-control input-md LocalityId" id="input-address_locality' + address_row + '" required="" class="form-control">';
  html += ' </div>';
  html += ' <?php } ?>';
  html += ' </div>';
  html += '  <?php } ?>';

  html += ' <?php if($this->config->get('config_store_location') == 'zipcode') { ?>';
  html += '  <div class="form-group">';
  html += ' <label class="col-md-12 control-label" for="zipcode"><?= $label_zipcode ?></label>';
  html += ' <div class="col-md-12">';
  html += ' <input  id="shipping_zipcode_input" type="text" value="<?php echo $zipcode; ?>" name="shipping_zipcode" class="form-control input-md"  required="">';
  html += ' </div>';
  html += ' </div>';
  html += ' <?php } else { ?>';
  html += ' <input id="shipping_zipcode" type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode">';
  html += ' <?php } ?>';
                                    
                                    
                                    <!-- Button -->

    /* html += '  <div class="form-group required">';
    html += '    <label class="col-sm-2 control-label" for="input-building_name' + address_row + '">Building name</label>';
    html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][building_name]" value="" placeholder="Building name" id="input-building_name' + address_row + '" class="form-control" /></div>';
    html += '  </div>';

    html += '  <div class="form-group required">';
    html += '    <label class="col-sm-2 control-label" for="input-landmark' + address_row + '">Landmark</label>';
    html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][landmark]" value="" placeholder="Landmark" id="input-landmark' + address_row + '" class="form-control" /></div>';
    html += '  </div>';

     html += '  <div class="form-group required">';
    html += '    <label class="col-sm-2 control-label" for="input-zipcode' + address_row + '">Zipcode</label>';
    html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][zipcode]" value="" placeholder="Zipcode" id="input-zipcode' + address_row + '" class="form-control" /></div>';
    html += '  </div>';

  html += '  <div class="form-group required">';
        html += '  <label class="col-sm-2 control-label" for="input-address-1' + address_row + '">Contact no</label>';
        html += '  <div class="col-sm-10">';
        html += '    <input type="text" name="address[' + address_row + '][contact_no]" value="" placeholder="Contact no" id="input-address-1' + address_row + '" class="form-control" />';
        html += '  </div>';
        html += '  </div>';
      
       html += '  <div class="form-group required">';
        html += '  <label class="col-sm-2 control-label" for="input-address-2' + address_row + '">Address</label>';
        html += '  <div class="col-sm-10">';
        html += '      <textarea name="address[' + address_row + '][address]" placeholder="Address" id="input-address-2' + address_row + '" class="form-control"></textarea>';
        html += '  </div>';
        html += '  </div>';*/
        
        html += '  <div class="form-group required">';
        html += '  <label class="col-sm-2 control-label" for="input-city' + address_row + '"><?php echo $entry_city; ?></label>';
        html += '  <div class="col-sm-10">';
        html += '      <select name="address[' + address_row + '][city_id]" class="form-control">';
               html += '                       <option value="">City</option>';
                       
                        <?php foreach($cities as $city){ ?>
        html += '          <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>';
                        <?php } ?>
        html += '      </select>';
        html += '  </div>';
        html += '  </div>';

        html += '  <div class="form-group">';
  html += '    <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>';
  html += '    <div class="col-sm-10"><label class="radio"><input type="radio" name="address[' + address_row + '][default]" value="1" /></label></div>';
  html += '  </div>';
        
        html += '</div>';

  $('#tab-general .tab-content').append(html);

  $('select[name=\'customer_group_id\']').trigger('change');

  $('#address-add').before('<li><a href="#tab-address' + address_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'#address a:first\').tab(\'show\'); $(\'a[href=\\\'#tab-address' + address_row + '\\\']\').parent().remove(); $(\'#tab-address' + address_row + '\').remove();"></i> <?php echo $tab_address; ?> ' + address_row + '</a></li>');

  $('#address a[href=\'#tab-address' + address_row + '\']').tab('show');

  address_row++;
}
//--></script> 
  <script type="text/javascript"><!--
function country(element, index, zone_id) {
  $.ajax({
    url: 'index.php?path=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
    dataType: 'json',
    beforeSend: function() {
      $('select[name=\'address[' + index + '][country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function() {
      $('.fa-spin').remove();
    },
    success: function(json) {
      if (json['postcode_required'] == '1') {
        $('input[name=\'address[' + index + '][postcode]\']').parent().parent().addClass('required');
      } else {
        $('input[name=\'address[' + index + '][postcode]\']').parent().parent().removeClass('required');
      }

      html = '<option value=""><?php echo $text_select; ?></option>';

      if (json['zone'] && json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
          html += '<option value="' + json['zone'][i]['zone_id'] + '"';

          if (json['zone'][i]['zone_id'] == zone_id) {
            html += ' selected="selected"';
          }

          html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0"><?php echo $text_none; ?></option>';
      }

      $('select[name=\'address[' + index + '][zone_id]\']').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('select[name$=\'[country_id]\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--
$('#history').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#history').load(this.href);
});

$('#history').load('index.php?path=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-history').on('click', function(e) {
  e.preventDefault();

  $.ajax({
    url: 'index.php?path=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
    beforeSend: function() {
      $('#button-history').button('loading');
    },
    complete: function() {
      $('#button-history').button('reset');
    },
    success: function(html) {
      $('.alert').remove();

      $('#history').html(html);

      $('#tab-history textarea[name=\'comment\']').val('');
    }
  });
});
//--></script> 
  <script type="text/javascript"><!--
$('#credit').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#credit').load(this.href);
});

$('#credit').load('index.php?path=sale/customer/credit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-credit').on('click', function(e) {

  if(encodeURIComponent($('#tab-credit input[name=\'amount\']').val())==0)
  {
    alert("please enter valid amount");
    return;
  }
  e.preventDefault();

        $.ajax({
    url: 'index.php?path=sale/customer/credit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'description=' + encodeURIComponent($('#tab-credit input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-credit input[name=\'amount\']').val()),
    beforeSend: function() {
      $('#button-credit').button('loading');
         },
    complete: function() {
      $('#button-credit').button('reset');
    },
    success: function(html) {
        $('.alert').remove();

      $('#credit').html(html);

       $('#tab-credit input[name=\'amount\']').val('');
      $('#tab-credit input[name=\'description\']').val('');
    }
  });
});
//--></script> 

  <script type="text/javascript"><!--
$('#reward').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();
  
  $('#reward').load(this.href);
});

$('#reward').load('index.php?path=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#referral').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

 $('#referral').load(this.href);
});

$('#referral').load('index.php?path=sale/customer/referral&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-reward').on('click', function(e) {
  if(encodeURIComponent($('#tab-reward input[name=\'description\']').val())=='')
  {
    alert("please enter valid description");
    return;
  }
  if(encodeURIComponent($('#tab-reward input[name=\'points\']').val())=='')
  {
    alert("please enter valid points");
    return;
  }  
  e.preventDefault();

  $.ajax({
    url: 'index.php?path=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
    beforeSend: function() {
      $('#button-reward').button('loading');
    },
    complete: function() {
      $('#button-reward').button('reset');
    },
    success: function(html) {
      $('.alert').remove();

      $('#reward').html(html);

       $('#tab-reward input[name=\'points\']').val('');
      $('#tab-reward input[name=\'description\']').val('');
       }
  });
});

$('#ip').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

 $('#ip').load(this.href);
});


$('#ip').load('index.php?path=sale/customer/ip&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('body').delegate('.button-ban-add', 'click', function() {
  var element = this;

  $.ajax({
    url: 'index.php?path=sale/customer/addbanip&token=<?php echo $token; ?>',
    type: 'post',
    dataType: 'json',
    data: 'ip=' + encodeURIComponent(this.value),
    beforeSend: function() {
      $(element).button('loading');
    },
    complete: function() {
      $(element).button('reset');
    },
    success: function(json) {
      $('.alert').remove();

      if (json['error']) {
         $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

        $('.alert').fadeIn('slow');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-danger btn-xs button-ban-remove"><i class="fa fa-minus-circle"></i> <?php echo $text_remove_ban_ip; ?></button>');
      }
    }
  });
});

$('body').delegate('.button-ban-remove', 'click', function() {
  var element = this;

  $.ajax({
    url: 'index.php?path=sale/customer/removebanip&token=<?php echo $token; ?>',
    type: 'post',
    dataType: 'json',
    data: 'ip=' + encodeURIComponent(this.value),
    beforeSend: function() {
      $(element).button('loading');
       },
    complete: function() {
      $(element).button('reset');
        },
    success: function(json) {
 $('.alert').remove();

      if (json['error']) {
         $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }

      
      if (json['success']) {
         $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-success btn-xs button-ban-add"><i class="fa fa-plus-circle"></i> <?php echo $text_add_ban_ip; ?></button>');
      }
    }
  });
});

$('#content').delegate('button[id^=\'button-custom-field\'], button[id^=\'button-address\']', 'click', function() {
  var node = this;
  
  $('#form-upload').remove();
  
  $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

  $('#form-upload input[name=\'file\']').trigger('click');
  
  if (typeof timer != 'undefined') {
      clearInterval(timer);
  }
  
  timer = setInterval(function() {
    if ($('#form-upload input[name=\'file\']').val() != '') {
      clearInterval(timer);
      
      $.ajax({
        url: 'index.php?path=tool/upload/upload&token=<?php echo $token; ?>',
        type: 'post',   
        dataType: 'json',
        data: new FormData($('#form-upload')[0]),
        cache: false,
        contentType: false,
        processData: false,   
        beforeSend: function() {
          $(node).button('loading');
        },
        complete: function() {
          $(node).button('reset');
        },    
        success: function(json) {
          $(node).parent().find('.text-danger').remove();
          
          if (json['error']) {
            $(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
          }
                
          if (json['success']) {
            alert(json['success']);
          }
          
          if (json['code']) {
            $(node).parent().find('input[type=\'hidden\']').attr('value', json['code']);
          }
        },      
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }, 500);
});

$('.date_dob').datetimepicker({
  pickTime: false,
  pickDate: true,
  format: 'DD/MM/YYYY',
  todayHighlight: true,
  autoclose: true,
  Default: 'MMMM YYYY'
});

$('.date').datetimepicker({
  pickTime: false
});

$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});

$('.time').datetimepicker({
  pickDate: false
}); 
//--></script></div>
<script type="text/javascript"><!--
    function save(type){
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'button';
            input.value = type;
            form = $("form[id^='form-']").append(input);
            form.submit();
    }
//--></script>

<style>
  #tab-general select, #tab-general textarea {
    max-width: 220px !important;
  }
  #tab-general input[type="radio"]{
      margin-left: 0 !important;
  }
</style>

<?php echo $footer; ?>

<style>
  #tab-general select, #tab-general textarea {
    max-width: 220px !important;
  }
  #tab-general input[type="radio"]{
      margin-left: 0 !important;
  }
</style>
 

    <style type="text/css">
        .pac-container {
          z-index: 99999999;
        }
        #map * {
            overflow:visible;
        }
    </style>
    
    

    <div class="GMapPopup">
        <div class="modal fade" id="GMapPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">

                            <div class="col-md-12">
                                <center> 
                                    <h2><?= $text_your_location ?> </h2>
                                </center>
                            </div>
                        </div>

                        <div id="wrapper">
                           
                            <div id="us1" style="width: 100%; height: 400px;"></div> 
                            <div id="us2" style="width: 100%; height: 400px;display: none"></div>
                           
                           <div id="over_map">

                                <div class="input-group">

                                    <input  name="modal_address_locality" type="text" id="gmap-input" class="form-control input-md LocalityId LocalityId2" required="" >                                                    
                                    <span class="input-group-btn">

                                        <button class="btn btn-default" id="detect_location" style="color: #333;background-color: #fff;border-color: #ccc;width: 150px;line-height: 2.438571; " type="button"  onclick="getLocation()"><i class="fa fa-location-arrow"></i> <?= $detect_location ?></button>

                                    </span>
                                </div>
                                
                           </div>
                        </div>
                        
                        <style>
                           #wrapper { position: relative; }
                           #over_map { position: absolute; top: 10px; padding-right: 12px;
                                        padding-left: 12px;  z-index: 99; width: 100%}
                        </style>

                        <script type="text/javascript">
                            

                            
                        </script>
                        <div class="row" style="margin-top: 10px;">
                            
                            <center>
                                <button id="saveLatLng" type="button" class="btn btn btn-primary" onclick="saveLatLng()">Ok</button>
                            </center>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/side-menu-script.js"></script>
    <script src="../front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/html5lightbox.js"></script>
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="../front/ui/theme/mvgv2/js/header-sticky.js"></script>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>

    <script type="text/javascript" src="<?= $base?>ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.2"></script>
    

    <script type="text/javascript">

            console.log("error_email");
            

            
            $('#us1').locationpicker({
                location: {
                    latitude: <?= $latitude?$latitude: 0.0236 ?>,
                    longitude: <?= $longitude?$longitude:37.9062 ?>
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId')
                },
                enableAutocomplete: true,
                zoom:13

            }); 


            function saveLatLng() {
                $('#GMapPopup').modal('hide');

                $('.LocalityId').val($('.LocalityId').val());
            }

            console.log("ehjdhj");
            jQuery(function($){
                console.log("mask");
               $("#shipping_zipcode_input").mask("<?= $zipcode_mask_number ?>",{autoclear:false,placeholder:"<?= $zipcode_mask ?>"});
            });

            function saveInAddressBook() {

                console.log($('#new-address-form').serialize());
                console.log("saveInAddressBook");
                $('.alert').remove();
                $('#save-address').button('saving');

                $('.help-block').hide();
                $('.has-error').removeClass('has-error');

                $error = false;
                var shipping_address = $('input[name="modal_address_street"]').val();
                var shipping_zipcode = $('input[name="shipping_zipcode"]').val();
                var shipping_city_id = $('input[name="shipping_city_id"]').val();
                var landmark = $('input[name="modal_address_locality"]').val();
                var building_name = $('input[name="modal_address_name"]').val();
                var flat_number = $('input[name="modal_address_flat"]').val();
                var address_type = $('input[name="modal_address_type"]:checked').val();
                //validate all fields

                if (landmark.length <= 0) {
                    $error = true;
                    $('input[name="modal_address_locality"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                if (building_name.length <= 0) {
                    $error = true;
                    $('input[name="modal_address_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                if (flat_number.length <= 0 ) {
                    $error = true;
                    $('input[name="modal_address_flat"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                    if (shipping_zipcode.length <= 0) {
                        $error = true;
                        $('input[name="shipping_zipcode"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                    }
                    
                <?php } ?>

                
                
                console.log(shipping_address+"**"+landmark+"**"+building_name+"**"+flat_number+"**"+address_type+"**");
                
                if (!$error) {
                //if (false) {

                    $valid_address = 0;
                    $.ajax({
                        url: 'index.php?path=account/address/addInAddressBookFromAccount',
                        type: 'post',
                        async: false,
                        data: $('#new-address-form').serialize(),
                        dataType: 'json',
                        cache: false,
                        success: function(json) {

                            console.log(json);
                            console.log("address add success");
                            if (json.status == 0) {
                                $('#address-message').html(json['message']);
                                $('#address-success-message').html('');
                               
                            } else {
                                console.log("address add success else");
                                $('#address-panel').html(json.html);
                                $('#addressModal').modal('hide');
                                 location=location;
                                return false;
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            $('#button-confirm').button('reset');
                            return false;
                        }
                    });
                    return true;
                } else {
                    /*$('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    $('#button-confirm').button('reset');*/
                    return false;
                }
            }

            function editAddressBook() {
                console.log("editAddressBook");
                $('.alert').remove();
                $('#save-address').button('saving');

                $('.help-block').hide();
                $('.has-error').removeClass('has-error');

                $error = false;
                var shipping_address = $('input[name="edit_modal_address_street"]').val();
                var shipping_zipcode = $('input[name="shipping_zipcode"]').val();
                var shipping_city_id = $('input[name="shipping_city_id"]').val();
                var landmark = $('input[name="edit_modal_address_locality"]').val();
                var building_name = $('input[name="edit_modal_address_name"]').val();
                var flat_number = $('input[name="edit_modal_address_flat"]').val();
                var address_type = $('input[name="edit_modal_address_type"]:checked').val();
                //validate all fields

                

                if (landmark.length <= 0) {
                    $error = true;
                    $('input[name="edit_modal_address_locality"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }
                
                if (building_name.length <= 0) {
                    $error = true;
                    $('input[name="edit_modal_address_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }

                /*if (flat_number.length <= 0 ) {
                    $error = true;
                    $('input[name="edit_modal_address_flat"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }*/

                /*if (shipping_zipcode.length <= 0) {
                    $error = true;
                    $('input[name="shipping_zipcode"]').parents('.form-group').addClass('has-error').find('.help-block').show();
                }*/

                console.log(shipping_address+"**"+landmark+"**"+building_name+"**"+flat_number+"**"+address_type);
                
                if (!$error) {

                    $valid_address = 0;
                    $.ajax({
                        url: 'index.php?path=account/address/editAddress',
                        type: 'post',
                        async: false,
                        data: $('#edit-address-form').serialize(),
                        dataType: 'json',
                        cache: false,
                        success: function(json) {

                            console.log(json);
                            console.log("address add success");
                            if (json.status == 0) {
                                $('#edit-address-message').html(json['message']);
                                $('#edit-address-success-message').html('');
                                
                            } else {
                                console.log("address add success else");
                                $('#address-panel').html(json.html);
                                $('#editAddressModal').modal('hide'); 
                                location = location;                               
                                return false;
                            }

                            location = location;
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $('#button-confirm').button('reset');
                            return false;
                        }
                    });
                    return true;
                } else {
                    /*$('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    $('#button-confirm').button('reset');*/
                    return false;
                }
            }


            function editAddressModal($address_id) {

                $('#edit-address-message').html('');
                $('#edit-address-success-message').html('');
                console.log($address_id);
                console.log("address_id");
                $.ajax({
                    url: 'index.php?path=account/address/getAddress',
                    type: 'post',
                    async: false,
                    data: {address_id: $address_id},
                    dataType: 'json',
                    cache: false,
                    success: function(json) {

                        console.log(json);
                        $('.edit-address-form-panel').html(json['html']);

                        $('#us2').locationpicker({
                            location: {
                                latitude: json['latitude'],
                                longitude: json['longitude']
                            },  
                            radius: 0,
                            inputBinding: {
                                latitudeInput: $('input[name="latitude"]'),
                                longitudeInput: $('input[name="longitude"]'),
                                locationNameInput: $('.edit_LocalityId')
                            },
                            enableAutocomplete: true,
                            zoom:13,

                        });
                        
                        console.log($('#us1').locationpicker('location'));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        $('#button-confirm').button('reset');
                        return false;
                    }
                });
            }



            

$('#activity').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

 $('#activity').load(this.href);
});


$('#activity').load('index.php?path=sale/customer/customeractivity&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');



    </script>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

      var __kdt = __kdt || [];

      var public_key = '<?php echo $konduto_public_key ?>';

      console.log("public_key");
      console.log(public_key);
    __kdt.push({"public_key": public_key}); // The public key identifies your store
    __kdt.push({"post_on_load": false});   
      (function() {
               var kdt = document.createElement('script');
               kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
               kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
               var s = document.getElementsByTagName('body')[0];

               console.log(s);
               s.parentNode.insertBefore(kdt, s);
                })();

                var visitorID;
        (function() {
          var period = 300;
          var limit = 20 * 1e3;
          var nTry = 0;
          var intervalID = setInterval(function() {
          var clear = limit/period <= ++nTry;

          console.log("visitorID trssy");
          if (typeof(Konduto.getVisitorID) !== "undefined") {
                   visitorID = window.Konduto.getVisitorID();
                   clear = true;
          }
          console.log("visitorID clear");
          if (clear) {
         clearInterval(intervalID);
        }
        }, period);
        })(visitorID);


        var page_category = 'my-address-page';
        (function() {
          var period = 300;
          var limit = 20 * 1e3;
          var nTry = 0;
          var intervalID = setInterval(function() {
                   var clear = limit/period <= ++nTry;
                   if (typeof(Konduto.sendEvent) !== "undefined") {

                    Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
                        clear = true;
                   }
                 if (clear) {
                clearInterval(intervalID);
             }
            },
            period);
            })(page_category);


</script>

<?php } ?>
<script type="text/javascript">

    function openGMap(address_id) {
        console.log(address_id);
        $('input[name^=\'selected_address\']').val(address_id);
        $("#GMapPopup").on('shown.bs.modal', function () {
            $('div#GMapPopup').show();
            $('#us1').locationpicker('autosize');

            console.log("efre");
            
        });
    }

    function GMapPopupInput() {

        var acInputs = document.getElementsByClassName("LocalityId2");

        

        var autocomplete = new google.maps.places.Autocomplete(acInputs);
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
                
            console.log("latitude");
            console.log(autocomplete);
            $('#us1').locationpicker({
                location: {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId2')
                },
                enableAutocomplete: true,
                zoom:13
                
            });
        });       
    }

    

    function initialize() {

        var acInputs = document.getElementsByClassName("LocalityId");

        for (var i = 0; i < acInputs.length; i++) {

            var autocomplete = new google.maps.places.Autocomplete(acInputs[i]);
            
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                console.log(autocomplete);


                
            });
        }
    }

    function getLocation() {

        $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $text_locating ?>');
        console.log("getLocation");

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        //var latlon = position.coords.latitude + "," + position.coords.longitude;
        console.log("showPosition");
        console.log(position);

        

        $('#us1').locationpicker({
            location: {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            },  
            radius: 0,
            inputBinding: {
                latitudeInput: $('input[name="latitude"]'),
                longitudeInput: $('input[name="longitude"]'),
                locationNameInput: $('.LocalityId')
            },
            enableAutocomplete: true,
            zoom:13
        });

        console.log($('#us1').locationpicker('location'));

        $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $detect_location ?>');
    }

    //initialize();

    $(document.body).on('mousedown', '.pac-container .pac-item', function(e) {
        console.log('click fired');
        $('#locateme').removeClass('disabled');
    });

    $(document.body).on('change', '.LocalityId', function(e) {
        console.log('change LocalityId');

        var address= $('#us1').locationpicker('location');
        console.log(address);
        /*if(address.addressComponents.streetName && address.addressComponents.streetNumber ) {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        } else {
            $('#street').val(address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetName);
        }*/

        

        if(!$('.LocalityId').val().length) {
            $('#locateme').addClass('disabled');
        }
    });

    $(document.body).on('change', '.edit_LocalityId', function(e) {
        console.log('change edit_LocalityId');

        var address= $('#us2').locationpicker('location');
        console.log(address);

        //$('.LocalityId').val();
        if(address.addressComponents.streetName && address.addressComponents.streetNumber ) {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        } else {
            $('#street').val(address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetName);
        }

        

        if(!$('.LocalityId').val().length) {
            $('#locateme').addClass('disabled');
        }
    });


    
 function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
    



    $('#contact').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#contact').load(this.href);
});

$('#contact').load('index.php?path=sale/customer/contact&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-contact').on('click', function(e) {

  if(encodeURIComponent($('#tab-contact input[name=\'contactpersonfirstname\']').val())==0)
  {
    alert("please enter contact person first name");
    return;
  }

  if(encodeURIComponent($('#tab-contact input[name=\'contactpersonemail\']').val())==0)
  {
    alert("please enter contact person email");
    return;
  }
  e.preventDefault();

        $.ajax({
    url: 'index.php?path=sale/customer/contact&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'html',
    data: 'firstname=' + encodeURIComponent($('#tab-contact input[name=\'contactpersonfirstname\']').val()) + '&lastname=' + encodeURIComponent($('#tab-contact input[name=\'contactpersonlastname\']').val())+ '&email=' + encodeURIComponent($('#tab-contact input[name=\'contactpersonemail\']').val())+'&phone=' + encodeURIComponent($('#tab-contact input[name=\'contactpersontelephone\']').val())+'&customer_contact_send=' + encodeURIComponent($('#tab-contact input[name=\'customer_contact_send\']').val())+'&contact_id=' + encodeURIComponent($('#tab-contact input[name=\'contactid\']').val()),
    beforeSend: function() {
      $('#button-contact').button('loading');
         },
    complete: function() {
      $('#button-contact').button('reset');
    },
    success: function(html) {
        $('.alert').remove();

      $('#contact').html(html);

       $('#tab-contact input[name=\'contactpersonfirstname\']').val('');
       $('#tab-contact input[name=\'contactid\']').val('0');
      $('#tab-contact input[name=\'contactpersonlastname\']').val('');
      $('#tab-contact input[name=\'contactpersonemail\']').val('');

      $('#tab-contact input[name=\'contactpersontelephone\']').val('');
      $('#tab-contact input[name=\'customer_contact_send\']').prop('checked', true);;

    }
  });
});

$('#button-configuration').on('click', function(e) {
e.preventDefault();

$.ajax({
    url: 'index.php?path=sale/customer/configuration&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'json',
    data: 'customer_category=' + encodeURIComponent($('#tab-configuration select[name=\'customer_category\']').val()) + '&account_manager=' + encodeURIComponent($('#tab-configuration select[name=\'account_manager\']').val())+ '&customer_experience=' + encodeURIComponent($('#tab-configuration select[name=\'customer_experience\']').val())+'&payment_terms=' + encodeURIComponent($('#tab-configuration select[name=\'payment_terms\']').val())+'&statement_duration=' + encodeURIComponent($('#tab-configuration select[name=\'statement_duration\']').val())+'&customer_id=<?php echo $customer_id; ?>',
    beforeSend: function() {
      $('#button-configuration').button('loading');
         },
    complete: function() {
      $('#button-configuration').button('reset');
    },
    success: function(json) {
        alert(json.message);
    }
  });
});


  $(document).delegate('.contactdelete', 'click', function () {
        var choice = confirm($(this).attr('data-confirm'));
        if (choice) {
          
            $contact_id = $(this).attr('data-contact-id');
          //alert($contact_id);
            $.ajax({
                url: 'index.php?path=sale/customer/DeleteCustomerContacts&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
                type: 'post',
                dataType: 'html',
                data: 'contact_id=' + $contact_id,
                success: function (html) {
                      $('.alert').remove();
                    
                     $('#contact').html(html);
                }
            });

            
        }
    });

    


       $(document).delegate('.contactedit', 'click', function () {
          
            
            var contact_id = $(this).attr('data-contact-id');
          


            $.ajax({
                url: 'index.php?path=sale/customer/getCustomerContact&token=<?php echo $token; ?>',
                type: 'get',
                data: {contact_id: contact_id},
                dataType: 'json',
                success: function (json) {
                     console.log(json['data']['lastname']);
                    console.log(json['data']['telephone']);
                    console.log(json['data']);
                      //$("#message_text").hide();
                        $('#input-contactpersonfirstname').val(json['data']['firstname']);
                        $('#input-contactpersonlastname').val(json['data']['lastname']);
                        $('#input-contactpersonemail').val(json['data']['email']);
                        $('#input-contactpersontelephone').val(json['data']['telephone']);
                        $('#input-contactid').val(json['data']['contact_id']);
                        $checked=json['data']['send'];
                        //alert($('#contactid').val());
                        if($checked=="1")
                        $('#customer_contact_send').prop( "checked", true );
                        else
                        $('#customer_contact_send').prop( "checked", false );
 
                }
            });
        
    });



    $('#button-password').on('click', function(e) {
e.preventDefault();

$.ajax({
    url: 'index.php?path=sale/customer/password&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    type: 'post',
    dataType: 'json',
    data: 'password-1=' + encodeURIComponent($('#tab-password input[name=\'password-1\']').val()) + '&confirm-1=' + encodeURIComponent($('#tab-password input[name=\'confirm-1\']').val())+ '&customer_id=<?php echo $customer_id; ?>',
    beforeSend: function() {
      $('#button-password').button('loading');
         },
    complete: function() {
      $('#button-password').button('reset');
    },
    success: function(json) {
        alert(json.message);
    }
  });
});

</script>
</body>

</html>
