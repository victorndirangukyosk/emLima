<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
  </ul>
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
  <div class="row">
	  <?php echo $column_left; ?>
	<?php if ($column_left && $column_right) { ?>
	<?php $class = 'col-sm-6'; ?>
	<?php } elseif ($column_left || $column_right) { ?>
	<?php $class = 'col-sm-9'; ?>
	<?php } else { ?>
	<?php $class = 'col-sm-12'; ?>
	<?php } ?>
	<div id="content" class="account-section <?php echo $class; ?>"><?php echo $content_top; ?>
		<div class="secion-row">
	  <div class="title"><?php echo $heading_title; ?></div>
	  <table class="table table-bordered table-hover">
		<thead>
		  <tr>
			<td class="text-left" colspan="2"><?php echo $text_order_detail; ?></td>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td class="text-left" style="width: 50%;"><?php if ($invoice_no) { ?>
			  <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
			  <?php } ?>
			  <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
			  <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
			<td class="text-left"><?php if ($payment_method) { ?>
			  <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
			  <?php } ?>
			  <?php if ($shipping_method) { ?>
			  <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
			  <?php } ?></td>
		  </tr>
		</tbody>
	  </table>
	  <table class="table table-bordered table-hover">
		<thead>
		  <tr>
			<td class="text-left" style="width: 50%;">
				To
			</td>
			<?php if ($shipping_address) { ?>
			<td class="text-left"><?php echo $text_shipping_address; ?></td>
			<?php } ?>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td class="text-left">
				<b><?= $text_name ?></b> <?php echo $shipping_name; ?><br />
				<b><?= $text_contact_no ?></b> <?php echo $shipping_contact_no; ?><br />
			</td>
			<?php if ($shipping_address) { ?>
			<td class="text-left">
				<?php echo $shipping_address; ?> <br />
				<?php echo $shipping_city; ?> 
			</td>
			<?php } ?>
		  </tr>
		</tbody>
	  </table>
	  <div class="table-responsive">
		  <table class="table table-bordered">
		<thead>
		<tr class="store_info">  
			<td colspan="4">
													   
				<div class="shipping_status_wrapper">
				   <div class="row">
					<div class="col-sm-4 col-xs-4">
						<div class="wizardno checkicon" id="go-back">
							<a>
								<i class="fa fa-check"></i>
								<dummy>1</dummy>
							</a>
							<p><?= $text_processing ?></p>
						</div>
					</div>
					<div class="col-sm-4 col-xs-4">
						<div class="wizardno <?php if(in_array($order_status_id, $this->config->get('config_complete_status')) || in_array($order_status_id, $this->config->get('config_shipped_status'))) echo 'checkicon'; ?>">
							<a>
								<dummy>2</dummy>
								<i class="fa fa-check"></i>
							</a>
							<p><?= $text_shipped ?></p>
						</div>
					</div>
					<div class="col-sm-4 col-xs-4">
						<div class="wizardno <?php if(in_array($order_status_id, $this->config->get('config_complete_status'))) echo 'checkicon'; ?>">
							<a>
								<dummy>3</dummy>
								<i class="fa fa-check"></i>
							</a>
							<p><?= $text_delivered ?></p>
						</div>
					</div>
				</div>                       
				</div>
				
				<?php if($delivery_timeslot){ ?>
				<hr />
				<div class="delivery_time_data">
					<?= $text_estimated_datetime ?>
					<span class="time">
						<?= $delivery_date.' ('.$delivery_timeslot.')' ?>
					</span>
				</div>
				<?php } ?>
				
			</td>  

			<?php if($status == 'Delivered'){ ?>
			<td colspan='2'>                            
			<?php }else{ ?>    
			<td>                            
			<?php } ?>    
			
				<div class="order_status_info">
					<center><b><?= $store_name ?></b></center>  
					<center><?= $store_address ?></center>
					<br />
					<center>
						<span class="label label-info status"><?= $status ?></span>
						<?php if(($status=='Submitted' || $status=='Received') && $status != 'Cancelled'){ ?>
						<br /><br />
						<a class="button danger" href="<?= $this->url->link('account/order/cancel','order_id='.$order_id) ?>">
							Cancel <br /> Order
						</a>
						<?php } ?>
					</center>
				</div>
			</td>
		</tr>
		</thead>
		</table>

	   	<table class="table table-bordered table-hover">
		  <thead>
			<tr>
				<td class="text-left"><?php echo $column_image; ?></td>
			  <td class="text-left"><?php echo $column_name; ?></td>
			  <td class="text-left"><?php echo $column_unit; ?></td>
			  <td class="text-left"><?php echo $column_model; ?></td>
			  <td class="text-right"><?php echo $column_quantity; ?></td>
			  <td class="text-right"><?php echo $column_price; ?></td>
			  <td class="text-right"><?php echo $column_total; ?></td>
			  <?php if ($products) { ?>
			  <td style="width: 20px;"></td>
			  <?php } ?>
			</tr>
		  </thead>
		  <tbody>
			<?php $i=0;  foreach ($products as $product) { ?>
			<tr>
				<td class="text-left"> <img style="margin-top: 12.5px;" src="<?= $product['image'] ?>" class="jvimage" /></td>
			  <td class="text-left"><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
				<br />
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
				<?php } ?></td>
				<td class="text-left"><?php echo $product['unit']; ?></td>
			  <td class="text-left"><?php echo $product['model']; ?></td>
			  <td class="text-right"><?php echo $product['quantity']; ?></td>
			  <td class="text-right"><?php echo $product['price']; ?></td>
			  <td class="text-right"><?php echo $product['total']; ?></td>
			  <td class="text-right" style="white-space: nowrap;"><?php if ($product['reorder']) { ?>
				<a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="<?php echo $button_reorder; ?>" class="btn-orange btn btn-primary"><i class="fa fa-shopping-cart"></i></a>
				<?php } ?>
				<a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a></td>
			</tr>
			<?php } ?>
			<tbody>

			<?php 
				foreach ($totals as $total) { ?>
				<tr>
					<td colspan="5"></td>
					<td class="right"><b><?php echo $total['title']; ?>:</b></td>
					<td class="right"><?php echo $total['text']; ?></td>
					
					<td></td>
					
				</tr>
				<?php } ?>
			</tbody>
			</table>



		</tbody>



		
	  </div>
	  <?php if ($comment) { ?>
	  <table class="table table-bordered table-hover">
		<thead>
		  <tr>
			<td class="text-left"><?php echo $text_comment; ?></td>
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td class="text-left"><?php echo $comment; ?></td>
		  </tr>
		</tbody>
	  </table>
	  <?php } ?>
	  <?php if ($histories) { ?>
	  <h3><?php echo $text_history; ?></h3>
	  <table class="table table-bordered table-hover">
		<thead>
		  <tr>
			<td class="text-left"><?php echo $column_date_added; ?></td>
			<td class="text-left"><?php echo $column_status; ?></td>
			<td class="text-left"><?php echo $column_comment; ?></td>
		  </tr>
		</thead>
		<tbody>
		  <?php foreach ($histories as $history) { ?>
		  <tr>
			<td class="text-left"><?php echo $history['date_added']; ?></td>
			<td class="text-left"><?php echo $history['status']; ?></td>
			<td class="text-left"><?php echo $history['comment']; ?></td>
		  </tr>
		  <?php } ?>
		</tbody>
	  </table>
	  <?php } ?>
	  <div class="buttons clearfix">
		<div class="pull-right">
			<a href="<?php echo $continue; ?>" class="btn-orange btn btn-primary"><?php echo $button_continue; ?></a></div>
	  </div>
	  </div>  
	  <?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>