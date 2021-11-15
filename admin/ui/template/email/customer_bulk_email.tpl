<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.1/dist/sweetalert2.min.css">
  <div class="page-header">
    <div class="container-fluid">
      <!--<div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="Add New Group"
          class="btn btn-success"><i class="fa fa-plus"></i></a>
      </div>-->
      <h1>Send Notification To Bulk Customers</h1>
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
        <h3 class="panel-title"><i class="fa fa-envelope-o"></i> Send Notification To Bulk Customers</h3>
      </div>
      <div class="panel-body">
        
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