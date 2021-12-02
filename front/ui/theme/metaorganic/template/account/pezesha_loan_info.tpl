<br>
<table id="employee" class="table table-bordered">
    <tbody id="loans_body">
        <tr>
            <td class="order_id">Laon ID</td>
            <td class="order_id"><?php echo $loan_id; ?></td>
        </tr>
        <tr>
            <td class="order_id">Order ID</td>
            <td class="order_id"><?php echo ' #'.implode(' #', $order_id); ?></td>
        </tr>
        <tr>
            <td class="order_id">Loan Amount</td>
            <td class="order_id"><?php echo $loan_amount; ?></td>
        </tr>
        <tr>
            <td class="order_id">Service Fee</td>
            <td class="order_id"><?php echo $sp_fee; ?></td>
        </tr>
        <tr>
            <td class="order_id">Processing Fee</td>
            <td class="order_id"><?php echo $pz_fee; ?></td>
        </tr>
        <tr>
            <td class="order_id">Total Amount</td>
            <td class="order_id"><?php echo $total_amount; ?></td>
        </tr>
        <tr>
            <td class="order_id">Interest Amount</td>
            <td class="order_id"><?php echo $interest_amount; ?></td>
        </tr>
        <tr>
            <td class="order_id">Interest</td>
            <td class="order_id"><?php echo $interest; ?></td>
        </tr>
        <tr>
            <td class="order_id">Status</td>
            <td class="order_id"><?php echo $status; ?></td>
        </tr>
        <tr>
            <td class="order_id">Application Date</td>
            <td class="order_id"><?php echo $application_date; ?></td>
        </tr>
        <tr>
            <td class="order_id">Funded Date</td>
            <td class="order_id"><?php echo $funded_time; ?></td>
        </tr>
        <tr>
            <td class="order_id">Due Date</td>
            <td class="order_id"><?php echo $due_date; ?></td>
        </tr>
    </tbody>
</table>