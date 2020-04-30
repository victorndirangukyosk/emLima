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
            <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
            <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
            <li><a href="#tab-referral" data-toggle="tab"><?php echo $tab_referral; ?></a></li>
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
                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                          <?php if ($error_email) { ?>
                          <div class="text-danger"><?php echo $error_email; ?></div>
                          <?php  } ?>
                        </div>
                      </div>

                      <!-- start -->
                      
                      <?php if($customer_group_id != $this->config->get('config_customer_group_id')) { ?>
                          <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-company_name"><?php echo $entry_company_name; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="company_name" value="<?php echo $company_name; ?>" placeholder="<?php echo $entry_company_name; ?>" id="input-company_name" class="form-control" />
                             <!--  <?php if ($error_company_name) { ?>
                              <div class="text-danger"><?php echo $error_company_name; ?></div>
                              <?php  } ?> -->
                            </div>
                          </div>

                          <div class="form-group required">
                            <label class="col-sm-2 control-label" for="input-company_address"><?php echo $entry_company_address; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="company_address" value="<?php echo $company_address; ?>" placeholder="<?php echo $entry_company_address; ?>" id="input-company_address" class="form-control" />
                             <!--  <?php if ($error_company_address) { ?>
                              <div class="text-danger"><?php echo $error_company_address; ?></div>
                              <?php  } ?> -->
                            </div>
                          </div>
                      <?php } ?>
                      

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

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_gender; ?></label>
                        <div class="col-sm-10">
                          <!-- <label class="radio-inline">  -->
                            <?php if($gender == 'male') {?> 
                                <input type="radio" name="sex" data-id="8" value="male" checked="checked" /> <?= $text_male ?> 
                            <?php } else {?>
                            <input type="radio" name="sex" data-id="8" value="male"/> <?= $text_male ?> 
                            <?php } ?>
                          <!-- </label> -->

                           <!-- <label class="radio-inline"> -->
                            <?php if($gender == 'female') {?> 
                                <input type="radio" name="sex" data-id="9" value="female" checked="checked"/> <?= $text_female ?>
                            <?php } else {?>
                            <input type="radio" name="sex" data-id="9" value="female"/> <?= $text_female ?> 
                            <?php } ?>
                             <!-- </label> -->

                           <!-- <label class="radio-inline"> -->
                            <?php if($gender == 'other') {?> 
                                <input type="radio" name="sex" data-id="8" value="other" checked="checked"/> <?= $text_other ?>
                            <?php } else {?>
                            <input type="radio" name="sex" data-id="8" value="other"/> <?= $text_other ?> 
                            <?php } ?>
                           <!-- </label> -->
                        </div>
                      </div>
                
                      

                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
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
                          <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                          <?php if ($error_confirm) { ?>
                          <div class="text-danger"><?php echo $error_confirm; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
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
                        <label class="col-sm-2 control-label" for="input-name<?php echo $address_row; ?>"><?= $entry_name ?></label>
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
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="street"><?= $text_stree_society_office?></label>
                            <div class="col-md-10">
                                <input type="text" name="address[<?php echo $address_row; ?>][building_name]" value="<?php echo $address['building_name']; ?>" placeholder="building_name" id="input-building_name<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['building_name'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['building_name']; ?></div>
                          <?php } ?>
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="Locality"><?= $text_locality?></label>
                            <div class="col-md-10">
                                <input type="text" name="address[<?php echo $address_row; ?>][landmark]" value="<?php echo $address['landmark']; ?>" placeholder="landmark" id="input-landmark<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['landmark'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['landmark']; ?></div>
                          <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="zipcode"><?= $label_zipcode ?></label>
                            <div class="col-md-10">
                                <!-- <input type="hidden" name="address[<?php echo $address_row; ?>][zipcode]" value="<?php echo $address['zipcode']; ?>" id="input-zipcode<?php echo $address_row; ?>" class="form-control" disabled/> -->
                                <input type="text" name="address[<?php echo $address_row; ?>][zipcode]" value="<?php echo $address['zipcode']; ?>" placeholder="zipcode" id="input-zipcode<?php echo $address_row; ?>" class="form-control"/>
                          <?php if (isset($error_address[$address_row]['zipcode'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['zipcode']; ?></div>
                          <?php } ?>
                            </div>
                        </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-1<?php echo $address_row; ?>"><?= $entry_contact_no ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][contact_no]" value="<?php echo $address['contact_no']; ?>" placeholder="Contact no" id="input-address-1<?php echo $address_row; ?>" class="form-control"/>
                          <?php if (isset($error_address[$address_row]['contact_no'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['contact_no']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <!-- <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-2<?php echo $address_row; ?>"><?= $entry_address ?></label>
                        <div class="col-sm-10">
                            <textarea name="address[<?php echo $address_row; ?>][address]" placeholder="Address" id="input-address-2<?php echo $address_row; ?>" class="form-control"><?php echo $address['address']; ?></textarea>
                            <?php if (isset($error_address[$address_row]['address'])) { ?>
                              <div class="text-danger"><?php echo $error_address[$address_row]['address']; ?></div>
                            <?php } ?>
                        </div>
                      </div> -->
                      <div class="form-group required disabled">
                        <label class="col-sm-2 control-label" for="input-city<?php echo $address_row; ?>"><?php echo $entry_city; ?></label>
                        <div class="col-sm-10">
                            <select name="address[<?php echo $address_row; ?>][city_id]" class="form-control" disabled>
                                <?php foreach($cities as $city){ ?>
                                <?php if($city['city_id'] == $address['city_id']){ ?>
                                <option selected="" value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                                <?php }else{ ?>
                                <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select> 
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>
                        <div class="col-sm-10">
                          <label class="radio">
                            <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
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
                  <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
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

            <div class="tab-pane" id="tab-referral">
              <div id="referral"></div>
              <br />
            </div>

            <?php } ?>
            <div class="tab-pane" id="tab-ip">
              <div id="ip"></div>
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
  html += '    <label class="col-sm-2 control-label" for="input-name' + address_row + '">Name</label>';
  html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][name]" value="" placeholder="Name" id="input-name' + address_row + '" class="form-control" /></div>';
  html += '  </div>';

    html += '  <div class="form-group required">';
    html += '    <label class="col-sm-2 control-label" for="input-flat_number' + address_row + '">Flat number</label>';
    html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][flat_number]" value="" placeholder="flat number" id="input-flat_number' + address_row + '" class="form-control" /></div>';
    html += '  </div>';

    html += '  <div class="form-group required">';
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
      
        /*html += '  <div class="form-group required">';
        html += '  <label class="col-sm-2 control-label" for="input-address-2' + address_row + '">Address</label>';
        html += '  <div class="col-sm-10">';
        html += '      <textarea name="address[' + address_row + '][address]" placeholder="Address" id="input-address-2' + address_row + '" class="form-control"></textarea>';
        html += '  </div>';
        html += '  </div>';*/
        
        /*html += '  <div class="form-group required">';
        html += '  <label class="col-sm-2 control-label" for="input-city' + address_row + '"><?php echo $entry_city; ?></label>';
        html += '  <div class="col-sm-10">';
        html += '      <select name="address[' + address_row + '][city_id]" class="form-control">';
                        <?php foreach($cities as $city){ ?>
        html += '          <option value="<?= $city['city_id'] ?>"><?= $city['name'] ?></option>';
                        <?php } ?>
        html += '      </select>';
        html += '  </div>';
        html += '  </div>';*/

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