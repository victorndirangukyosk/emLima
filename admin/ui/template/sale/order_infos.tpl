<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
          <div class="pull-right">
                    <?php if ($this->user->isLogged() && $this->session->data['token'] != NULL) { ?> 
                    <a href="" data-toggle="tooltip" title="Update Order Status" class="btn btn-primary"><i class="fa fa-cogs"></i></a>
                    <?php }  else { ?> 
                    <a href="" data-toggle="tooltip" title="Login" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <?php } ?>
	</div>
	  <h1><?php echo $heading_title; ?></h1>
	</div>
  </div>
  <div class="container-fluid">
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?> <?php echo "#".$order_id; ?></h3>
	  </div>
	  <div class="panel-body">
		<ul class="nav nav-tabs">
		  <li class="active"><a href="#tab-product" data-toggle="tab">Updated Products</a></li>
		  <li><a href="#tab-original-product" data-toggle="tab">Ordered Products</a></li>
		</ul>
		<div class="tab-content">
		  <div class="tab-pane active" id="tab-product">
			
				<hr />
				 	<tr>
					<td class="left">
						<b><?= $text_store ?></b>:
						<span class="store_name"><?= $store_name ?></span>
					</td>
						<td class="left">
							
							<span style="line-height: 30px;">
								<b><?= $text_status ?></b>: 
								<span class="status">
									<?php echo $status; ?>
								</span>
							</span>

						</td>
					
				</tr>            
				
				<tr>
					<td><b><?= $text_expected_delivery_time ?></b></td>
					<td><?= date('m/d/Y',strtotime($delivery_date)).' ('.$delivery_timeslot.')' ?>
					</td>				
				</tr>		

				<hr>
				<table class="table table-bordered table-hover">
				  <thead>
					<tr>

					  <td class="text-left"><?php echo $column_model; ?></td>
					  <td class="text-left"><?php echo $column_name; ?></td>
                                          <td class="text-left">Product Notes</td>
					  <td class="text-right"><?php echo trim($column_quantity,"( Ordered )!") ; ?></td>
					  <td class="text-right"><?php echo $column_unit; ?></td>

					  <td class="text-right"><?php echo $column_price; ?></td>
					  <td class="text-right"><?php echo $column_total; ?></td>
					  
					</tr>
				  </thead>
				  <tbody>
					<?php $i=0;  foreach ($products as $product) { ?>
					<tr>
						<td class="text-left"><?php echo $product['model']; ?></td>
					   <td class="text-left"><?php echo $product['name']; ?>
						<?php foreach ($product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?>
						<?php if($product['product_type'] == 'replacable') { ?>
                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                             <?= $text_replacable ?>
                            </span>
                        <?php } else { ?>
                            <span  class="badge badge-danger replacable" data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                <?= $text_not_replacable ?>
                            </span>
                        <?php } ?>


                        <?php foreach ($products_status as $product_status) { ?>
                            <?php if(trim($product['name']) == trim($product_status->product_name) && $product['unit'] == $product_status->unit) { $is_true = false; ?>

                            	<span> <i class="fa fa-arrow-right" aria-hidden="true"></i></span>

                                <?php if($product_status->status == 'Remaining') {  $is_true = true;?>
                                    <span class="badge badge-warning">
                                        <?= $text_remaining ?>
                                    </span>
                                <?php } ?>

                                <?php if($product_status->status == 'In-Transit') { $is_true = true; ?>
                                    <span class="badge badge-info">
                                        <?= $text_intransit ?>
                                    </span>
                                <?php } ?>

                                <?php if($product_status->status == 'Completed') { $is_true = true; ?>
                                    <span class="badge badge-success">
                                        <?= $text_completed ?>
                                    </span>
                                <?php } ?>

                                <?php if($product_status->status == 'Canceled') { $is_true = true; ?>
                                    <span class="badge badge-danger">
                                        <?= $text_cancelled ?>
                                    </span>
                                <?php } ?>

                                <?php if(!$is_true) { ?>
                                    <span class="badge badge-primary">
                                        <?= $product_status->status ?>
                                    </span>
                                <?php } ?>
                        <?php } } ?>
						
						<br>						
							<?php echo $product['produce_type']; ?>
						</td>
					  <td class="text-left"><?php echo $product['product_note']; ?></td>
					  <td class="text-right"><?php echo $product['quantity']; ?></td>
						<td class="text-right"><?php echo $product['unit']; ?></td>

					  <td class="text-right"><?php echo $product['price']; ?></td>
					  <td class="text-right"><?php echo $product['total']; ?></td>
					  
					</tr>
					<?php } ?>
					<tbody>

					<?php 
						foreach ($totals as $total) { ?>

						<?php if(!$this->user->isVendor()) { ?>

							
								<tr>
									<td colspan="5"></td>
									<td class="text-right"><b><?php echo $total['title']; ?>:</b></td>
									<td class="text-right"><?php echo $total['text']; ?></td>
									
									
								</tr>
							

						<?php } else { ?>

							
								<tr>
									<td colspan="4"></td>
									<td class="text-right"><b><?php echo $total['title']; ?>:</b></td>
									<td class="text-right"><?php echo $total['text']; ?></td>
									
									
								</tr>
							

						<?php } ?>

						
						
						<?php } ?>
					</tbody>
					</table>



				</tbody>



				<hr />
			
			
			
		  </div>

		  <!-- orignal ordered products start-->
		   <?php if($is_edited  || 1==1) {?> 


		<div class="tab-pane" id="tab-original-product">     
					
				<table class="table table-bordered table-hover">
				  <thead>
					<tr>
						<td class="text-left"><?php echo $column_model; ?></td>
					  <td class="text-left"><?php echo $column_name; ?></td>
                                          <td class="text-left">Product Notes</td>
					  <td class="text-right"><?php echo $column_quantity; ?></td>
					  <td class="text-right"><?php echo $column_unit.'( Ordered )'; ?></td>

					    <td class="text-right"><?php echo $column_quantity_update; ?></td>
						  <td class="text-right"><?php echo $column_unit.'( Updated )'; ?></td>

					  <td class="text-right"><?php echo $column_price; ?></td>
					  <td class="text-right"><?php echo $column_total; ?></td>
					  
					</tr>
				  </thead>
				  <tbody>
 	 
				  
					<?php $i=0;  foreach ($original_products as $original_product) { ?>
					<tr>
						<td class="text-left"><?php echo $original_product['model']; ?></td>
					   <td class="text-left"><?php echo $original_product['name']; ?>
						<?php foreach ($original_product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?>
						<?php if($original_product['product_type'] == 'replacable') { ?>
                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                             <?= $text_replacable ?>
                            </span>
                        <?php } else { ?>
                            <span  class="badge badge-danger replacable" data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                <?= $text_not_replacable ?>
                            </span>
                        <?php } ?>
<br>						
							<?php echo $original_product['produce_type']; ?>
							
						</td>
	                                  <td class="text-left"><?php echo $original_product['product_note']; ?></td>
					  <td class="text-right"><?php echo $original_product['quantity']; ?></td>
						<td class="text-right"><?php echo $original_product['unit']; ?></td>

						  <td class="text-right"><?php echo $original_product['quantity_updated']; ?></td>
						  <td class="text-right"><?php echo $original_product['unit_updated']; ?></td>

					  <td class="text-right"><?php echo $original_product['price']; ?></td>
					  <td class="text-right"><?php echo $original_product['total']; ?></td>
					  
					</tr>
					<?php } ?>
					<tbody style="display:none;">

						<tr>
							<td colspan="3"></td>
							<td class="text-right"><b>Total:</b></td>
							<td class="text-right"><?php echo $original_final_total; ?></td>
							
							
						</tr>
					</tbody>
					</table>
				</tbody>
		  </div>

		  <?php } ?> 

		   <?php if(!$this->user->isVendor() && $is_edited) {?>

			   <div class="tab-pane" id="tab-difference-product">     
					
					<hr />
				 <tr>
					<td class="left">
						<b><?= $text_store ?></b>:
						<span class="store_name"><?= $store_name ?></span>
					</td>
						<td class="left">
							
							<span style="line-height: 30px;">
								<b><?= $text_status ?></b>: 
								<span class="status">
									<?php echo $status; ?>
								</span>
							</span>

						</td>


					<!--<?php  if(!$this->user->isVendor()){ ?>
						<td class="left">
							<b><?= $text_commision ?></b>:
							<span class="commision">
								<?= $commission.'%' ?>
							</span>
						</td>
					<?php } ?>-->
					
				</tr>            
				<tr style="line-height: 40px;">
					<td colspan='2'>
						<b><?= $text_expected_delivery_time ?></b>:

						<span class="delivery_time_data">
							<?= date('d-m-Y',strtotime($delivery_date)).' ('.$delivery_timeslot.')' ?>
						</span>

					</td>
					
				</tr>	
				<hr>

				<hr>

					<table class="table table-bordered table-hover">
					  <thead>
						<tr>
							<td class="text-left"><?php echo $column_model; ?></td>
						  <td class="text-left"><?php echo $column_name; ?></td>						  
						  <td class="text-right"><?php echo $column_quantity; ?></td>
						  <td class="text-right"><?php echo $column_unit.'( Ordered )'; ?></td>

						  <td class="text-right"><?php echo $column_quantity_update; ?></td>
						  <td class="text-right"><?php echo $column_unit.'( Updated )'; ?></td>

						  <td class="text-right"><?php echo $column_price; ?></td>
						  <td class="text-right"><?php echo $column_total; ?></td>
						  
						</tr>
					  </thead>
					  <tbody>
						<?php $i=0;  foreach ($difference_products as $difference_product) { ?>
						<tr>
							<td class="text-left"><?php echo $difference_product['model']; ?></td>
						   <td class="text-left"><?php echo $difference_product['name']; ?>
							<?php foreach ($difference_product['option'] as $option) { ?>
							<br />
							&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
							<?php } ?>
							<?php if($difference_product['product_type'] == 'replacable') { ?>
	                            <span class="badge badge-success replacable" data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
	                             <?= $text_replacable ?>
	                            </span>
	                        <?php } else { ?>
	                            <span  class="badge badge-danger replacable" data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
	                                <?= $text_not_replacable ?>
	                            </span>
	                        <?php } ?>

								
							</td>
						  
						  <td class="text-right"><?php echo $difference_product['quantity']; ?></td>
							<td class="text-right"><?php echo $difference_product['unit']; ?></td>

						  <td class="text-right"><?php echo $difference_product['quantity_updated']; ?></td>
						  <td class="text-right"><?php echo $difference_product['unit_updated']; ?></td>
						  <td class="text-right"><?php echo $difference_product['price']; ?></td>
						  <td class="text-right"><?php echo $difference_product['total']; ?></td>
						  
						</tr>
						<?php } ?>
						</table>
					</tbody>
			  </div>
		  <?php } ?>
		</div>
	  </div>
	</div>
<?php echo $footer; ?> 
