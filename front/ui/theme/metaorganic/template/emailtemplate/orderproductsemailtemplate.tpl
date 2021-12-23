<table class="table table-bordered">
    <tbody>
        <tr>
            <td>S.NO</td>
            <td>PRODUCT NAME</td>
            <td>QUANTITY</td>
            <td>UNIT PRICE</td>
            <td>TOTAL</td>
        </tr>
        <?php foreach($products as $product) { ?>
        <tr>
            <td>S.NO</td>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['quantity']; ?><?php echo $product['unit']; ?></td>
            <td><?php echo $product['price']; ?></td>
            <td><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
