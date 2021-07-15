<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left">First Name</td>
        <td class="text-left">Last Name</td>
        <td class="text-left">Email</td>
        <td class="text-left">Phone No</td>
        <td class="text-left">Send Invoice</td>
        <td class="text-left">Action</td>
      </tr>
    </thead>
    <tbody>
      <?php if ($contacts) { ?>
      <?php foreach ($contacts as $contact) { ?>
      <tr>
        <td class="text-left"><?php echo $contact['firstname']; ?></td>
        <td class="text-left"><?php echo $contact['lastname']; ?></td>
        <td class="text-left"><?php echo $contact['email']; ?></td>
        <td class="text-left"><?php echo $contact['telephone']; ?></td>
       <?php if($contact['send'] == '1') { ?>
                                <td><input type="checkbox" checked id="send_invoice_required" name="send_invoice_required" data-contactid="<?php echo $contact['contact_id']; ?>" title="Send invoice" disabled></td>
                                <?php } else { ?>
                                <td><input type="checkbox"   id="send_invoice_required" name="send_invoice_required" data-contactid="<?php echo $contact['contact_id']; ?>" title="Don't send invoice" disabled ?></td>
                                <?php } ?>
                                <td> 
                                <a  class="btn btn-success contactedit"   data-contact-id="<?php echo $contact['contact_id']; ?>" data-toggle="tooltip" title="Edit contact"><i class="fa fa-edit"></i></a>
                                <a data-confirm="Delete contact!" class="btn btn-success contactdelete"   data-contact-id="<?php echo $contact['contact_id']; ?>" data-toggle="tooltip" title="Delete contact"><i class="fa fa-trash"></i></a>
                                </td>
                                 </tr>
      <?php } ?>
      
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
