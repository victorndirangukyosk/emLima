<table class="table table-bordered">
    <thead>
        <tr>
            <th>Amount</th>
            <th>Intesrest</th>
            <th>Rate</th>
            <th>Fee</th>
            <th>Duration</th>
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