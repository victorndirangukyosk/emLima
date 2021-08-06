<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <td class="text-center">Customer Name</td>
                <td class="text-center">Company Name</td>
                <td class="text-center">Email</td>
                <td class="text-center">Telephone</td>
                <td class="text-center">Action</td>
            </tr>
        </thead>
        <tbody>
            <?php if ($assignedcustomers) { ?>
            <?php foreach ($assignedcustomers as $assignedcustomer) { ?>
            <tr>
                <td class="text-left"><?php echo $assignedcustomer['name']; ?></td>
                <td class="text-left"><?php echo $assignedcustomer['company_name']; ?></td>
                <td class="text-left"><?php echo $assignedcustomer['email']; ?></td>
                <td class="text-left"><?php echo $assignedcustomer['telephone']; ?></td>
                <td class="text-center"><a href="#" data-toggle="tooltip" title="Un Assign Customer" id="unassigncustomer" data-customerexperience="<?php echo $assignedcustomer['customer_experience_id']; ?>" data-customer="<?php echo $assignedcustomer['customer_id']; ?>" class="btn btn-primary"><i class="fa fa-minus"></i></a></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
