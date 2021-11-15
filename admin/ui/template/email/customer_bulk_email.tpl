<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" onclick="save('save')" form="form-email-template" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
		        </div>
			<h1>Send Notification To Bulk Customers</h1>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> Send Notification To Bulk Customers</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-email-template" class="form-horizontal">
					<div class="tab-pane active in" id="tab-general">
						<ul class="nav nav-tabs" id="language">
                                                    <li><a href="#notification" data-toggle="tab">Notification</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane" id="notification">
                                                                <div class="form-group required">
									<label class="col-sm-2 control-label" for="input-subject">Company Name</label>
									<div class="col-sm-10">
										<input type="text" name="company_name" value="" placeholder="Type Here Company Name" id="input-company-name" class="form-control input-full-width" />
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-subject">Subject</label>
									<div class="col-sm-10">
										<input type="text" name="subject" value="" placeholder="Subject" id="input-subject" class="form-control input-full-width" />
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-notification-description">Description
									</label>
									<div class="col-sm-10">
										<textarea name="email_template_description" placeholder="Mail Description" id="input-notification-description" class="form-control"></textarea>
									</div>
								</div>
								
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-sms-desctiption">SMS Description</label>
									 <div class="col-sm-10">
										<textarea name="sms_description" placeholder="SMS Description" id="input-sms-description" class="form-control"></textarea>
									</div>
								</div>

								

							        </div>
								</div>

								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-mobile-notification-title">Mobile Notification Title</label>
									 <div class="col-sm-10">
										<textarea name="mobile_notification_title" placeholder="Mobile Notification Title" id="input-mobile_notification_title" class="form-control"></textarea>
									</div>
								</div>

								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-mobile-notification-message">Mobile Notification Message</label>
									 <div class="col-sm-10">
										<textarea name="mobile_notification_message" placeholder="Mobile Notification Message" id="input-mobile_notification_message" class="form-control"></textarea>
									</div>
								</div>

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
	<?php if( $text_editor == 'summernote' ) { ?>
		$('#input-notification-description').summernote({
			height: 300
		});
	<?php } else if ( $text_editor == 'tinymce' ) { ?>
		$('#input-notification-description').tinymce({
			script_url : 'ui/javascript/tinymce/tinymce.min.js',
			plugins: "print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists textcolor wordcount contextmenu colorpicker textpattern code",
			//plugins: "code",
			theme: 'modern',

  			toolbar1: "formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code",
			  target_list: [
			   {title: 'None', value: ''},
			   {title: 'Same page', value: '_self'},
			   {title: 'New page', value: '_blank'},
			   {title: 'LIghtbox', value: '_lightbox'}
			  ],
			  image_advtab: true,
			height : 500  
			 //menubar: "file,edit,insert,view,format,table,tools"
		});
	<?php } ?>

function shortCode(button) {
    $(".jumbotron").slideToggle();
}

//--></script> 
  <script type="text/javascript"><!--
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
	$('input[name="company_name"]').amsifySuggestags({
		suggestionsAction : {
			url : 'admin/index.php?path=dropdowns/dropdowns/companynames&token=<?php echo $token; ?>',
			beforeSend : function() {
			console.info('beforeSend');
			},
			success: function(data) {
		        console.info(data);
			},
			error: function() {
			console.info('error');
			},
			complete: function(data) {
			console.info('complete');
			}
		}
	});
</script>
<?php echo $footer; ?>