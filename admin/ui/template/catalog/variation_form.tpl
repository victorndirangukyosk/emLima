<table id="variations" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<td class="">
				<?= $entry_select ?>
			</td>
			<td class="">
				<?= $entry_name ?>
			</td>
			<td>
				<?= $entry_price ?>
			</td>
			<td>
				<?= $entry_special_price ?>
			</td>
		</tr>
	</thead>
	<tbody>
		
			<?php $variation_row=0; ?>
            <?php foreach ($product_variations as $product_variation) { ?>
            <tr id="variation-row<?php echo $variation_row; ?>">
            	<input type="hidden" name="variation_id[]" value="<?php echo $product_variation['id'] ?>">
                <td class="text-left">
                	<input type="checkbox" name="product_variation[variation][]" value="<?php echo $product_variation['id'] ?>"><br>
             	</td>
                <td >
                    <?php echo $product_variation['name']; ?>
                </td>
                
                <td>
               	 	<input type="text" class="form-control" name="product_variation[price][]" value="" required>
                </td>
                <td class="text-left">
                	<input type="text" class="form-control" name="product_variation[special_price][]" value="">
               </td>

            </tr>
            <?php $variation_row++; ?>
            <?php } ?>

	</tbody>
</table>