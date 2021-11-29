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
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?= $tab_general ?></a></li>
                        <li><a href="#tab-contact" data-toggle="tab"><?= $tab_contact ?></a></li>
                        <li><a href="#tab-password" data-toggle="tab"><?= $tab_password ?></a></li>
                        <?php if($shopper_id){ ?>
                        <li><a href="#tab-credit" data-toggle="tab"><?= $tab_wallet ?></a></li>
                        <?php } ?>
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
                            <div class="form-group">
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
                        <div class="tab-pane" id="tab-contact">                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-mobile"><?php echo $entry_mobile; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="mobile" value="<?php echo $mobile; ?>" placeholder="<?php echo $entry_mobile; ?>" id="input-mobile" class="form-control" />
                                    <?php if ($error_mobile) { ?>
                                    <div class="text-danger"><?php echo $error_mobile; ?></div>
                                    <?php } ?>
                                </div>
                            </div>                    
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_telephone; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
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
                                    <div class="text-danger"><?php echo $error_city_id; ?></div>
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
                        </div>
                        <div class="tab-pane" id="tab-password">
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
                                  <input type="text" name="order_id" value="" placeholder="Order ID" id="input-order_id" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-credit-description"><?php echo $entry_description; ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-credit-description" class="form-control" />
                                </div>
                            </div>                    
                            <div class="text-right">
                                <button type="button" id="button-credit" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_credit_add; ?></button>
                            </div>
                        </div>
                    </div>                   
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    
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
	form = $("#form-user").append(input);
	form.submit();
}

<?php if($shopper_id) { ?>

$('#credit').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#credit').load(this.href);
});

$('#credit').load('index.php?path=shopper/shopper/credit&token=<?php echo $token; ?>&shopper_id=<?php echo $shopper_id; ?>');

$('#button-credit').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?path=shopper/shopper/credit&token=<?php echo $token; ?>&shopper_id=<?php echo $shopper_id; ?>',
        type: 'post',
        dataType: 'html',
        data: 'description=' + encodeURIComponent($('#tab-credit input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-credit input[name=\'amount\']').val())+ '&order_id=' + encodeURIComponent($('#tab-credit input[name=\'order_id\']').val()),
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

<?php } ?>
//--></script>
<?php echo $footer; ?> 