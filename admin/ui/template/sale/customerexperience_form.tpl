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
                        <?php if ($user_id) { ?>
                        <li><a href="#tab-assign-customers" data-toggle="tab"><?php echo $tab_assign_customers; ?></a></li>
                        <li><a href="#tab-assigned-customers" data-toggle="tab"><?php echo $tab_assigned_customers; ?></a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" autocomplete="off" />
                                    <?php if ($error_username) { ?>
                                    <div class="text-danger"><?php echo $error_username; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="hidden"  name="user_group_id" id="input-user-group" value="<?php echo $this->config->get('config_account_manager_group_id'); ?>">
                            <!--<div class="form-group">
                              <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
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
                            </div>-->
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
                                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                    <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
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
                        <?php if ($user_id) { ?>
                        <div class="tab-pane" id="tab-assign-customers">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-assign-username">Assign Company</label>
                                <div class="col-sm-10">
                                    <input type="text" name="assign_customers" value="" placeholder="Type Company Name" id="input-assign-customer" class="form-control" />
                                    <div id="assign_customers_select" class="well well-sm" style="height: 150px; overflow: auto;">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button id="button-assign-customer" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Assign Company</button>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-assigned-customers">
                            <div id="assignedcustomers"></div>
                            <br />
                        </div>
                        <?php } ?>
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
// Customers
    $('input[name=\'assign_customers\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?path=sale/customerexperience/getUnassignedCustomers&token=<?php echo $token; ?>',
                type: 'post',
                dataType: 'json',
                data: {name: $("input[name=assign_customers]").val()},
                success: function (json) {
                    if (!json.length) {
                        var result = [
                            {
                                label: 'No matches found',
                                value: ''
                            }
                        ];
                        response(result);
                    } else if ($("input[name=assign_customers]").val() == '' || $("input[name=assign_customers]").val() == null) {
                        var result = [
                            {
                                label: 'Type company name',
                                value: ''
                            }
                        ];
                        response(result);
                    } else {
                        response($.map(json, function (item) {
                            return {
                                label: item['company_name'],
                                value: item['customer_id']
                            }
                        }));
                    }
                }
            });
        },
        'select': function (item) {
            $('input[name=\'assign_customers\']').val('');
            $('#assign_customers_select' + item['value']).remove();
            $('#assign_customers_select').append('<div id="assign_customers_select' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="assign_customers_select[]" value="' + item['value'] + '" /></div>');
        }
    });
    $('#assign_customers_select').delegate('.fa-minus-circle', 'click', function () {
        $(this).parent().remove();
    });
    $('#button-assign-customer').on('click', function (e) {
        e.preventDefault();
        var val = [];
        $('input[name=\'assign_customers_select[]\']').each(function (i) {
            val[i] = $(this).val();
        });
        if (val.length == 0) {
            $(".alert").show();
            $('.alert').html('Please select atleast one customer!');
            $('.alert').delay(5000).fadeOut('slow');
            return false;
        }
        console.log(val);
        $.ajax({
            url: 'index.php?path=sale/customerexperience/assigncustomer&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: {assigncustomer: val, customer_experience_id: <?php echo $user_id; ?> },
            beforeSend: function () {
                $('#button-assign-customer').button('loading');
            },
            complete: function () {
                $('#button-assign-customer').button('reset');
            },
            success: function (json) {
                $('.alert').html('Customer assigned successfully!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                console.log(json);
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            }
        });
    });

    $(document).delegate('#unassigncustomer', 'click', function (e) {
        e.preventDefault();
        var customer_experience_id = $(this).attr('data-customerexperience');
        var customer_id = $(this).attr('data-customer');
        console.log(customer_experience_id);
        console.log(customer_id);
        console.log('UN ASSIGN');
        $.ajax({
            url: 'index.php?path=sale/customerexperience/unassigncustomer&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: {unassigncustomer: customer_id, customer_experience_id: customer_experience_id},
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
                $('.alert').html('Company un assigned successfully!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                console.log(json);
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            }
        });
    });
    
$('#assignedcustomers').delegate('.pagination a', 'click', function(e) {
e.preventDefault();

$('#assignedcustomers').load(this.href);
});

$('#assignedcustomers').load('index.php?path=sale/customerexperience/getassignedcustomers&token=<?php echo $token; ?>&customer_experience_id=<?php echo $user_id; ?>');
    function assign_customers() {
        var val = [];
        $('input[name=\'assign_customers_select[]\']').each(function (i) {
            val[i] = $(this).val();
        });
        if (val.length == 0) {
            /*$(".alert").show();
             $('.alert').html('Please select atleast one customer!');
             $('.alert').delay(5000).fadeOut('slow');*/
            return false;
        }
        console.log(val);
        $.ajax({
            url: 'index.php?path=sale/customerexperience/assigncustomer&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: {assigncustomer: val, customer_experience_id: <?php echo $user_id; ?> },
            beforeSend: function () {
                $('#button-assign-customer').button('loading');
            },
            complete: function () {
                $('#button-assign-customer').button('reset');
            },
            success: function (json) {
                $('.alert').html('Customer assigned successfully!');
                $(".alert").attr('class', 'alert alert-success');
                $(".alert").show();
                console.log(json);
            }
        });
    }
//--></script>
<?php echo $footer; ?> 
