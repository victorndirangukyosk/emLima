<table class="table table-bordered">
    <thead>
        <tr>
            <th>Amount(KES)</th>
            <th>Intesrest(KES)</th>
            <th>Rate(%)</th>
            <th>Fee(KES)</th>
            <th>Duration(Days)</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!$error) { ?>
        <tr>
            <td><?php echo $data['amount']; ?></td>
            <td><?php echo $data['interest']; ?></td>
            <td><?php echo $data['rate']; ?></td>
            <td><?php echo $data['fee']; ?></td>
            <td><?php echo $data['duration']; ?></td>
        </tr>
        <?php } ?>
        <?php if($error) { ?>
        <tr>
            <?php echo $message; ?>   
        </tr>
        <?php } ?>
    </tbody>
</table>