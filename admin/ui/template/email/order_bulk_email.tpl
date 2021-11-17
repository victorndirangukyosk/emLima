<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
                            <button type="button" id="send_notification" name="send_notification" form="form-email-template" data-toggle="tooltip" title="Send Notification" class="btn btn-success" data-original-title="Send"><i class="fa fa-envelope"></i></button>
		        </div>
			<h1>Send Notification To Bulk Orders</h1>
		</div>
	</div>
	<div class="container-fluid">
            <div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i><div class="sucmsg"></div>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
            
		<div class="alert alert-danger" style="display:none;"></div>
            
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> Send Notification To Bulk Orders</h3>
			</div>
			<div class="panel-body">
				<form action="" method="post" enctype="multipart/form-data" id="form-email-template" class="form-horizontal">
					<div class="tab-pane active in" id="tab-general">
						<ul class="nav nav-tabs" id="language">
                                                    <li><a href="#notification" data-toggle="tab">Notification</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="notification">
                                                                <div class="form-group required">
									<label class="col-sm-2 control-label" for="input-subject">Order ID's</label>
									<div class="col-sm-10">
										<input type="text" name="order_id" value="" placeholder="Type Here Order ID's" id="input-order-id" class="form-control input-full-width" />
  										<input type="hidden" name="selected" value="" id="selected"/>
									</div>
								</div>
                                                            <div class="form-group required">
                                                                <label class="col-sm-2 control-label" for="input-delivery-date">Delivery Date</label>
                                                                <div class="col-sm-5">
                                                                    <div class="input-group date">
                                                                        <input type="text" name="filter_delivery_date" value="" placeholder="Delivery Date" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                                                                        <span class="input-group-btn">
                                                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                                        </span>
                                                                    </div>	
                                                                </div>
                                                            </div>
                                                                <div class="form-group required">
                                                               <label class="col-sm-2 control-label" for="input-delivery-time-slot">Delivery Time Slot</label>
									<div class="col-sm-5">
										<select name="filter_delivery_time_slot" id="input-delivery-time-slot" class="form-control">
                                                                                <option value="">Select Delivery Time Slot</option>
                                                                                <?php foreach ($time_slots as $time_slot) { ?>
                                                                                <option value="<?php echo $time_slot['timeslot']; ?>"><?php echo $time_slot['timeslot']; ?></option>
                                                                                <?php } ?>
                                                                                </select>
									</div>  
                                                                </div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-subject">Subject</label>
									<div class="col-sm-10">
										<input type="text" name="subject" id="subject" value="" placeholder="Subject" id="input-subject" class="form-control input-full-width" />
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-notification-description">Description
									</label>
									<div class="col-sm-10">
										<textarea name="email_template_description" placeholder="Mail Description" id="input-notification-description" class="form-control input-full-width"></textarea>
									</div>
								</div>
								
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-sms-desctiption">SMS Description</label>
									 <div class="col-sm-10">
										<textarea name="sms_description" id="sms_description" placeholder="SMS Description" id="input-sms-description" class="form-control input-full-width"></textarea>
									</div>
								</div>

								

							        </div>
								</div>

								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-mobile-notification-title">Mobile Notification Title</label>
									 <div class="col-sm-10">
										<textarea name="mobile_notification_title" placeholder="Mobile Notification Title" id="mobile_notification_title" class="form-control input-full-width"></textarea>
									</div>
								</div>

								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-mobile-notification-message">Mobile Notification Message</label>
									 <div class="col-sm-10">
										<textarea name="mobile_notification_message" placeholder="Mobile Notification Message" id="mobile_notification_message" class="form-control input-full-width"></textarea>
									</div>
								</div>

							</div>
                                </form>
			</div>
		</div>
	</div>
</div>
<style>
    .jumbotron {
        padding-left: 20px !important;
        padding-right: 20px !important;
        padding-top: 20px;
        padding-bottom: 5px;
    }

    .html-button {
        margin-top: 10px;
    }

    @media (min-width: 768px) {
        .modal-dialog {
            width: 725px !important;
            margin: 30px auto;
        }
    }

</style>

<script type="text/javascript"><!--
$('.date').datetimepicker({
            pickTime: false,
              widgetParent: 'body'
});

$('#language a:first').tab('show');
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
//--></script>
<link rel="stylesheet" type="text/css" href="ui/amsify/amsify.suggestags.css">
<script type="text/javascript" src="ui/amsify/jquery.amsify.suggestags.js"></script>
<script type="text/javascript">
	$('input[name="order_id"]').amsifySuggestags({
		suggestionsAction : {
                        type: 'POST',
			url : 'admin/index.php?path=dropdowns/dropdowns/orderids&token=<?php echo $token; ?>',
			beforeSend : function(xhr, settings) {
                        settings.data += '&'+$.param({ filter_order_id: $('input[class="amsify-suggestags-input"]').val() });
			console.info('beforeSend');
			},
			success: function(data) {
                        console.info('success');
		        console.info(data);
			},
			error: function() {
			console.info('error');
			},
			complete: function(data) {
			console.info('complete');
			}
		},
        defaultTagClass : 'bg-primary',
        whiteList: true,
        getSelected : function(value) {
        console.info('getSelected');
        $('#selected').val(value);
        }        
	});
</script>
<script type="text/javascript">
$('#send_notification').on('click', function () {
var sms_description = $('#sms_description').val();
var mobile_notification_title = $('#mobile_notification_title').val();
var mobile_notification_message = $('#mobile_notification_message').val();
var subject = $('#subject').val();
var email_description = $('#input-notification-description').val();

$('.alert-danger').hide();
$('.alert-success').hide();

if($("#input-delivery-date").val() != '' && $("#input-delivery-time-slot").val() == '') {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Delivery Time Slot Required!');
}

if($("#input-delivery-date").val() == '' && $("#input-delivery-time-slot").val() != '') {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Delivery Date Required!');
}

if($("#input-delivery-date").val() == '' && $("#input-delivery-time-slot").val() == '' && $("#selected").val() == '')
{
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Select Atleaset One Dropdown!');
}

if(subject.length <= 0) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mail Subject Required!');
return false;
}

if(subject.length > 30) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mail Subject Must Be Between 1 And 30 Characters!');
return false;
}

if(email_description.length <= 0) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mail Description Required!');
return false;
}

if(sms_description.length <= 0) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> SMS Description Required!');
return false;
}

if(sms_description.length > 160) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> SMS Description Must Be Between 1 And 160 Characters!');
return false;
}

if(mobile_notification_title.length <= 0) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mobile Notification Title Required!');
return false;
}

if(mobile_notification_title.length > 50) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mobile Notification Title Must Be 1 And 50 Characters!');
return false;
}

if(mobile_notification_message.length <= 0) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mobile Notification Message Required!');
return false;
}

if(mobile_notification_message.length > 50) {
$('.alert-danger').show();
$('.alert-danger').html("");    
$('.alert-danger').html('<i class="fa fa-exclamation-circle"></i> Mobile Notification Message Must Be 1 And 50 Characters!');
return false;
}
                $.ajax({
                    url: 'index.php?path=email/bulk_email/sendbulknotification&token=<?php echo $token; ?>',
                    type: 'post',
                    dataType: 'json',
                    data: 'subject=' + encodeURIComponent($('#subject').val()) + '&sms_description='+ encodeURIComponent($('#sms_description').val()) + '&mobile_notification_title='+ encodeURIComponent($('#mobile_notification_title').val()) + '&mobile_notification_message='+ encodeURIComponent($('#mobile_notification_message').val()) + '&selected='+ encodeURIComponent($('#selected').val()) + '&email_description='+ encodeURIComponent($("#input-notification-description").val()) + '&delivery_date='+ encodeURIComponent($("#input-delivery-date").val()) + '&delivery_time_slot='+ encodeURIComponent($("#input-delivery-time-slot").val()),
                    success: function (json) {
                    console.log(json);
                    }
                });
});
</script>
<?php echo $footer; ?>
