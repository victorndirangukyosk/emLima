<?php echo $header; ?>

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

<div class="page-header">
    <h2>
        <?php echo $heading_title; ?>
        <div class='buttons-wrap pull-right'>
            <button onclick="$('#form').submit();" type="submit" form="form-paytm" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        </div>
    </h2>    
</div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?= $tab_general ?></a></li>
        <li><a href="#tab-contact" data-toggle="tab"><?= $tab_contact_details ?></a></li>
        <li><a href="#tab-bank" data-toggle="tab"><?= $tab_bank_details ?></a></li>
    </ul>
    
    <br />
    
    <div class="tab-content">
        <div class="tab-pane active" id="tab-general">

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
                <label class="control-label col-sm-3"><?= $entry_lastname ?></label>
                <div class="col-sm-9">
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control" />
                    <?php if ($error_lastname) { ?>
                    <span class="text-danger"><?php echo $error_latname; ?></span>
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
        <div class="tab-pane" id="tab-contact">                            
            <div class="form-group">
                <label class="control-label col-sm-3"><?= $entry_mobile ?></label>
                <div class="col-sm-9">
                    <input type="text" name="mobile" value="<?php echo $mobile; ?>" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3"><?= $entry_telephone ?></label>
                <div class="col-sm-9">
                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control" />
                </div>
            </div>
            <!--<div class="form-group">
                <label class="control-label col-sm-3">State</label>
                <div class="col-sm-9">
                    <input type="text" name="state" value="<?php echo $state; ?>" class="form-control" />
                </div>
            </div>   -->         
            <div class="form-group">
                <label class="control-label col-sm-3"><?= $entry_city ?></label>
                <div class="col-sm-9">
                    <input type="text" name="city" value="<?php echo $city ?>" class="form-control" />
                </div>
            </div>  
            <div class="form-group">
                <label class="control-label col-sm-3"><?= $entry_address ?></label>
                <div class="col-sm-9">
                    <textarea name="address" class="form-control"><?= $address ?></textarea>
                </div>
            </div> 

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
    </div>                     
</form>

<?php echo $footer; ?> 

