<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" onclick="save('save')" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
				<button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
				<button type="submit" onclick="save('new')" form="form-store" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>		
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
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
					
						
						
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
							<div class="text-danger"><?php echo $error_name; ?></div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group">

					<label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
						<div class="col-sm-10">
							<a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail">
								<img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $thumb; ?>" />
								
							</a>
							<input type="hidden" name="logo" value="<?php echo $logo; ?>" id="input-logo" />
						</div>
					</div>

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-seo_url"><?php echo $entry_seo_url ?></label>
						<div class="col-sm-10">
							<input type="text" name="seo_url" value="<?php echo $seo_url; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo_url" class="form-control" />
							<?php if ($error_seo_url) { ?>
							<div class="text-danger"><?php echo $error_seo_url; ?></div>
							<?php } ?>
						</div>
					</div>
					
					
					<div class="form-group">
		                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_geocode; ?>"><?php echo $entry_cityzipcodes; ?></span></label>
		                <div class="col-sm-10 apply-css">
		                  <input type="text" name="stores" value="" placeholder="<?php echo $entry_cityzipcodes; ?>" id="input-product" class="form-control" />
		                  <div id="stores" class="well well-sm">
		                    <?php foreach ($stores as $stores) { ?>
		                    <div id="stores<?php echo $stores['store_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $stores['name']; ?>
		                      <input type="hidden" name="stores[]" value="<?php echo $stores['store_id']; ?>" />
		                    </div>
		                    <?php } ?>
		                  </div>
		                </div>
		            </div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-currency"><?= $entry_status ?></label>
						<div class="col-sm-10">
							<select name="status" class="form-control">
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
						
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
	function save(type) {
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'button';
			input.value = type;
			form = $("form[id^='form-']").append(input);
			form.submit();
		}


$(document).delegate('.remove','click', function(){
	$(this).parent().parent().remove();
});


$('input[name=\'stores\']').autocomplete({

	'source': function(request, response) {

		var city_id = $('#selectCity').find(":selected").val()

		$.ajax({
			url: 'index.php?path=setting/store/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',		
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['store_id']
					}
				}));

				$('.apply-css ul').css({'width': '200px', 'height': '140px', 'overflow': 'auto'});
			}
		});
	},
	'select': function(item) {
		$('input[name=\'stores\']').val('');
		
		$('#stores' + item['value']).remove();
		
		$('#stores').append('<div id="stores' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="stores[]" value="' + item['value'] + '" /></div>');	
	}
});

$('#stores').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
</script>