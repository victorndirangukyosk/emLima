<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
				<button type="submit" class="btn btn-danger" onclick="clear_data();" ><i class="fa fa-trash-o"></i> </button> 

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
			<!-- <div class="panel-heading">
	        	<h3 class="panel-title"><i class="fa fa-trash-o"></i> <?php echo $heading_title; ?></h3>
	        </div> -->

			<div class="panel-body">

				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#tab-load" data-toggle="tab"><?php echo $tab_load; ?></a>
					</li>
					
					<li><a href="#tab-reset" data-toggle="tab"><?php echo $tab_reset; ?></a></li>
				</ul>

				<div class="tab-content">

					<div class="tab-pane active" id="tab-load">

						<form action="<?= $load_action ?>" method="post" id="load-form" class="form-horizontal">

						    
			            	<label class="col-sm-2 control-label"><?php echo $heading_title; ?></label>
				            <div class="col-sm-10">
				              
					            <div class="radio">
							      <label><input type="radio" name="load_db" value="in"><?php echo $text_india; ?></label>
							    </div>
							    <div class="radio">
							      <label><input type="radio" name="load_db" value="br"><?php echo $text_brazil; ?></label>
							    </div>
							    <div class="radio">
							      <label><input type="radio" name="load_db" value="us"><?php echo $text_usa; ?></label>
							    </div>
						    </div>

						    <center>
						    	<button class="btn btn-primary" type="submit"> Load </button>
						    </center>
					  	</form>
				  	</div>

				  	<div class="tab-pane" id="tab-reset">
				  		<form action="<?= $action ?>" method="post" id="reset-form" class="form-horizontal">

						    <div class="form-group">
				            	<label class="col-sm-2 control-label"><?php echo $heading_title; ?></label>
					            <div class="col-sm-10">
					              <div class="well well-sm" style="height: 150px; overflow: auto;">
					               	<div class="checkbox">
							      <label><input type="checkbox" name="checked[]" value="deleteCustomers">Customers</label>
							    </div>
							    <div class="checkbox">
							      <label><input type="checkbox" name="checked[]" value="deleteOrders">Orders</label>
							    </div>
							    <div class="checkbox">
							      <label><input type="checkbox" name="checked[]" value="deleteStores">Stores</label>
							    </div>
							    <div class="checkbox">
							      <label><input type="checkbox" name="checked[]" value="deleteVendors">Vendors</label>
							    </div>
							    <div class="checkbox">
							      <label><input type="checkbox" name="checked[]" value="deleteOrderTransactions">Order Transactions</label>
							    </div>

							    <div class="checkbox">
							      <label><input type="checkbox" name="checked[]" value="deleteMiscellaneous">Miscellaneous</label>
							    </div>
							    

							    

				            </div>
					  	</form>

					  	<form action="<?= $reset_factory_action ?>" method="post" id="reset-factory-form" class="form-horizontal">

					  		<button class="btn btn-danger" type="button" onclick="resetfactory()"> Reset factory data </button>

					  	</form>
				  	</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
	function clear_data() {
		if(confirm(' You want to clear data?')) {
			$('form#reset-form').submit();
		}
		return false;
	}

	function resetfactory() {
		if(confirm(' All custom settings will be wiped?')) {
			$('form#reset-factory-form').submit();
		}
		return false;
	}

</script>
