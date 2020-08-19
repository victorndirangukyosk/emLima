<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.css">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="#" data-toggle="modal" data-target="#addCustomerModal"
          title="Add Customer To This Group" class="btn btn-success"><i class="fa fa-plus"></i></a>
      </div>
      </button>
      <h1><?php echo $group['name'] ; ?> Customers</h1>
    </div>
  </div>
  <div class="container-fluid">
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>Customers In Group</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-email-groups">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">
                    Customer Name
                  </td>
                  <td class="text-left">
                    Email Address
                  </td>
                  <td class="text-right">
                    Phone Number
                  </td>
                  <td class="text-right">
                    Action
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($customers) { ?>
                <?php foreach ($customers as $customer) { ?>
                <tr>
                  <td class="text-left"><?php echo $customer['name'] ; ?></td>
                  <td class="text-left"><?php echo $customer['email']; ?></td>
                  <td class="text-left"><?php echo $customer['telephone']; ?></td>
                  <td class="text-right">
                    <a href="#" data-delete-url="<?php echo $customer['delete']; ?>" data-toggle="tooltip"
                      title="Remove Customer From Group" class="btn btn-danger group-customer-remove"><i
                        class="fa fa-times"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6">No customers in this group</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Customer To Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <input type="hidden" id="add-customer-id">
              <label class="control-label" for="input-customer">Customer Name</label>
              <input type="text" name="filter_customer" placeholder="Customer Name" id="input-customer"
                class="form-control" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="add-customer-button" type="button" class="btn btn-primary">Add Customer</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>

  $('#add-customer-button').click(function() {
    const customerId = $('#add-customer-id').val();
    const groupId = "<?php echo $group['id']; ?>";

    window.location = 'index.php?path=email/groups/addCustomerToGroup&token=<?php echo $token; ?>&group_id=' + groupId + '&customer_id=' + customerId
  });

  $('input[name=\'filter_customer\']').autocomplete({
    'source': function (request, response) {
      $companyName = "";
      $.ajax({
        url: 'index.php?path=sale/customer/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) + '&filter_company=' + $companyName,
        dataType: 'json',
        success: function (json) {
          response($.map(json, function (item) {
            return {
              label: item['name'],
              value: item['customer_id']
            }
          }));
        }
      });
    },
    'select': function (item) {
      $('#add-customer-id').val(item['value']);
      $('input[name=\'filter_customer\']').val(item['label']);
    }
  });

  $('.group-customer-remove').click(function (e) {
    e.preventDefault();
    e.stopPropagation();

    const deleteUrl = $(e.target).data('deleteUrl');

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        window.location = deleteUrl;
      }
    });
  })
</script>
<?php echo $footer; ?>