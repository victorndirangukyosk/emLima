<?php if(count($most_purchased) > 0) { foreach($most_purchased as $products) { ?>
<tr>
    <td><?php echo $products['name']; ?></td>
    <td><?php echo $products['unit']; ?></td>
    <td><?php echo $products['quantity']; ?></td>
    <td>
        <a href="#" onclick="getPurchaseHistory( < ? = $products['product_id'] ? > )" data-toggle="modal"
           data-dismiss="modal" title="View Purchase History" data-target="#productHistory"
           class="btn btn-info" style="border-radius: 0px;"><i class="fa fa-info"></i></a>
    </td>
</tr>
<?php } } else { ?>
<tr>
    <td class="text-center" colspan="6"><?php echo 'No Results Found'; ?></td>
</tr>
<?php } ?>
