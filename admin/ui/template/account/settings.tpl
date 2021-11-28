<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button onclick="$('#form').submit();" type="submit" form="form-paytm" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
            </div>
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

            <div class="panel-body">

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

                    <ul class="nav nav-tabs">
                        <li ><a href="#tab-general" data-toggle="tab"><?= $tab_general ?></a></li>
                        <li class="active"><a href="#tab-contact" data-toggle="tab"><?= $tab_contact_details ?></a></li>
                        <li><a href="#tab-connect" data-toggle="tab"><?= $tab_bank_details ?></a></li>
                        <!-- <li><a href="#tab-connect" data-toggle="tab"> Bank Details</a></li> -->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab-general">

                            <div class="form-group required">
                                <label class="control-label col-sm-3"><?= $entry_username ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" value="<?php echo $username; ?>" class="form-control" />
                                    <?php if ($error_username) { ?>
                                    <span class="text-danger"><?php echo $error_username; ?></span>
                                    <?php } ?>              
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-3"><?= $entry_firstname ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>" />
                                    <?php if ($error_firstname) { ?>
                                    <span class="text-danger"><?php echo $error_firstname; ?></span>
                                    <?php } ?>         
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="control-label col-sm-3">Company name</label>
                                <div class="col-sm-9">
                                   <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control" />
                                    <?php if ($error_lastname) { ?>
                                    <span class="text-danger"><?php echo $error_lastname; ?></span>
                                    <?php } ?>       
                                </div>
                            </div>                    

                            <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_email ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="email" value="<?php echo $email; ?>" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_tin_no ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="tin_no" value="<?php echo $tin_no; ?>" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane active" id="tab-contact">                            
                            <!-- <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_mobile ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="mobile" value="<?php echo $mobile; ?>" class="form-control" />
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="input-email"><?= $entry_mobile ?></label>
                                <div class="col-sm-9 input-group" style="left: 14px;">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
                                    <input type="text" name="mobile" value="<?php echo $mobile; ?>" placeholder="<?= $entry_mobile ?>"  class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" />
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                 <label class="control-label col-sm-3"><?= $entry_telephone ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control" />
                                </div> 
                            </div> -->

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="input-email"><?= $entry_telephone ?></label>
                                <div class="col-sm-9 input-group" style="left: 14px;">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">+<?= $this->config->get('config_telephone_code') ?></button> 
                                        
                                    </span>
                                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?= $entry_telephone ?>"  class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 &amp;&amp; event.charCode <= 57" minlength="9" maxlength="9" />
                                </div>
                            </div>

                          <!--  <div class="form-group">
                                <label class="control-label col-sm-3">State</label>
                                <div class="col-sm-9">
                                    <input type="text" name="state" value="<?php echo $state; ?>" class="form-control" />
                                </div>
                            </div> --> 
                            <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_city ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="city" value="<?php echo $city; ?>" class="form-control" />
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_address ?></label>
                                <div class="col-sm-9">
                                    <textarea name="address" id="input-address" class="form-control"><?= $address ?></textarea>
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
                                    locationName : "<?=  $address; ?>",
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
                        <div class="tab-pane" id="tab-bank">  
                            <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_ifsc_code ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="ifsc_code" value="<?php echo $ifsc_code; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3"><?= $entry_bank_account_no ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="bank_acc_no" value="<?php echo $bank_acc_no; ?>" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <!-- tab-connect -->
                        <div class="tab-pane" id="tab-connect">
                            <div id="connect"></div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-bank_account_number">Bank A/c No</label>
                                <div class="col-sm-10">
                                    <input type="bank_account_number" name="bank_account_number" value="<?php echo $bank_account_number; ?>" id="input-bank_account_number" class="form-control" autocomplete="off" />
                                    <?php if ($error_bank_account_number) { ?>
                                    <div class="text-danger"><?php echo $error_bank_account_number; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-bank_account_name">Bank Account Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>"  id="input-bank_account_name" class="form-control" />
                                    <?php if ($error_bank_account_name) { ?>
                                    <div class="text-danger"><?php echo $error_bank_account_name; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-bank_name">Bank Name</label>
                                <div class="col-sm-10">
                                    <input type="bank_name" name="bank_name" value="<?php echo $bank_name; ?>"  id="input-bank_name" class="form-control" autocomplete="off" />
                                    <?php if ($error_bank_name) { ?>
                                    <div class="text-danger"><?php echo $error_bank_name; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group required">
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

<?php echo $footer; ?> 

