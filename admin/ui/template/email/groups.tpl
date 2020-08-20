<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.css">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="Add New Group"
          class="btn btn-success"><i class="fa fa-plus"></i></a>
      </div>
      <h1>Email Groups</h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Email Groups List</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-email-groups">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">
                    Group Name
                  </td>
                  <td class="text-left">
                    Group Description
                  </td>
                  <td class="text-right">
                    Actions
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($groups) { ?>
                <?php foreach ($groups as $group) { ?>
                <tr>
                  <td class="text-left"><?php echo $group['name']; ?></td>
                  <td class="text-left"><?php echo $group['description']; ?></td>
                  <td class="text-right">
                    <a href="<?php echo $group['customers']; ?>" data-toggle="tooltip" title="View Customers in Group"
                      class="btn btn-primary"><i class="fa fa-users"></i></a>
                    <a href="<?php echo $group['edit']; ?>" data-toggle="tooltip" title="Edit Group"
                      class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <a href="#" data-delete-url="<?php echo $group['delete']; ?>" data-toggle="tooltip"
                      title="Delete Group" class="btn btn-danger group-delete"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6">No Email Groups Found</td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
  $('.group-delete').click(function (e) {
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