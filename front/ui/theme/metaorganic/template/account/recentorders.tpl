<?php if ($recent_orders) { ?>
<?php foreach ($recent_orders as $ro) { ?>
<tr>
    <td><?php echo $ro['order_id']; ?></td>
    <td><?php echo $ro['name']; ?></td>
    <td><?php echo $ro['date_added']; ?></td>
    <td><?php echo $ro['delivery_date']; ?></td>
    <td>
        <!--
        code shoud be modified as in order_list<a data-confirm="Products in this order will be added to cart !!" class="btn btn-success download"
          data-store-id="<?= ACTIVE_STORE_ID ?>" data-toggle="tooltip"
          value="<?php echo $ro['order_id']; ?>" title="Add To Cart/Reorder"><i
            class="fa fa-cart-plus"></i></a>-->

        <a href="<?php echo $ro['href'];?>" target="_blank" data-toggle="tooltip" title="View Order"
           class="btn btn-success">
            <i class="fa fa-eye"></i>
        </a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
    <td class="text-center" colspan="6"><?php echo 'No Orders'; ?></td>
</tr>
<?php } ?>