<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" onclick="save('new')" form="form-user" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
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
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                                    <?php if ($error_username) { ?>
                                    <div class="text-danger"><?php echo $error_username; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="first_name" value="<?php echo $first_name; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                                    <?php if ($error_first_name) { ?>
                                    <div class="text-danger"><?php echo $error_first_name; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="last_name" value="<?php echo $last_name; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                                    <?php if ($error_last_name) { ?>
                                    <div class="text-danger"><?php echo $error_last_name; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                    <?php if ($error_email) { ?>
                                    <div class="text-danger"><?php echo $error_email; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="mobile" value="<?php echo $mobile; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" maxlength=10 onkeypress="return isNumberKey(event)"  />
                                    <?php if ($error_mobile) { ?>
                                    <div class="text-danger"><?php echo $error_mobile; ?></div>
                                    <?php  } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-farmer-type">Farmer Type</label>
                                <div class="col-sm-10">
                                    <select name="farmer_type" id="input-farmer-type" class="form-control">
                                        <option value="Commercial" <?php if(isset($farmer_type) && $farmer_type == 'Commercial') { ?> selected="selected" <?php } ?> >Commercial</option>
                                        <option value="Smallholder" <?php if(isset($farmer_type) && $farmer_type == 'Smallholder') { ?> selected="selected" <?php } ?> >Smallholder</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-farm-size">Farm Size</label>
                                <div class="col-sm-10">
                                    <input type="text" name="farm_size" value="<?php echo $farm_size; ?>" placeholder="Farm Size" id="input-farm-size" class="form-control" />
                                    <?php if ($error_farm_size) { ?>
                                    <div class="text-danger"><?php echo $error_farm_size; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-farm-size-type">Farm Size Type</label>
                                <div class="col-sm-10">
                                    <select name="farm_size_type" id="input-farm-size-type" class="form-control">
                                        <option value="Acres" <?php if(isset($farm_size_type) && $farm_size_type == 'Acres') { ?> selected="selected" <?php } ?> >Acres</option>
                                        <option value="Hectares" <?php if(isset($farm_size_type) && $farm_size_type == 'Hectares') { ?> selected="selected" <?php } ?> >Hectares</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-irrigation-type">Irrigation Type</label>
                                <div class="col-sm-10">
                                    <select name="irrigation_type" id="input-irrigation-type" class="form-control">
                                        <option value="Piped" <?php if(isset($irrigation_type) && $irrigation_type == 'Piped') { ?> selected="selected" <?php } ?> >Piped</option>
                                        <option value="Natural" <?php if(isset($irrigation_type) && $irrigation_type == 'Natural') { ?> selected="selected" <?php } ?> >Natural</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-location">Location</label>
                                <div class="col-sm-10">
                                    <input type="text" name="location" value="<?php echo $location; ?>" placeholder="Location" id="input-location" class="form-control" />
                                    <?php if ($error_location) { ?>
                                    <div class="text-danger"><?php echo $error_location; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-description">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" name="description" value="<?php echo $description; ?>" placeholder="Description" id="input-description" class="form-control" />
                                    <?php if ($error_description) { ?>
                                    <div class="text-danger"><?php echo $error_description; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-organization">Organization</label>
                                <div class="col-sm-10">
                                    <input type="text" name="organization" value="<?php echo $organization; ?>" placeholder="Farmer Organization" id="input-organization" class="form-control" />
                                    <?php if ($error_organization) { ?>
                                    <div class="text-danger"><?php echo $error_organization; ?></div>
                                    <?php } ?>
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
                                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
                                    <?php if ($error_confirm) { ?>
                                    <div class="text-danger"><?php echo $error_confirm; ?></div>
                                    <?php  } ?>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
function save(type) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'button';
        input.value = type;
        assign_customers();
        form = $("form[id^='form-']").append(input);
        form.submit();
    }
//--></script>
<?php echo $footer; ?> 
