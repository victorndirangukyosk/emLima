<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="Add New Group" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
		  <div class="pull-right">
			<button type="button" data-toggle="tooltip" title="Show Filter" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
			<button type="button" data-toggle="tooltip" title="Hide Filter" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
		  </div>		
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-username">Group Name</label>
                <input type="text" name="filter_group_name" value="<?php echo $filter_group_name; ?>" placeholder="Email Group Name" id="input-group-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-group-description">Group Description</label>
                <input type="text" name="filter_group_description" value="<?php echo $filter_group_description; ?>" placeholder="Group Description" id="input-group-description" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
          </div>
        </div>	  
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-email-groups">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
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
                  <td class="text-center"><?php if (in_array($group['id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $group['id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $group['id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $group['name']; ?></td>
                  <td class="text-left"><?php echo $group['description']; ?></td>
                  <td class="text-right">
                    <a href="<?php echo $group['customers']; ?>" data-toggle="tooltip" title="View Customers in Group" class="btn btn-primary"><i class="fa fa-users"></i></a>
                    <a href="<?php echo $group['edit']; ?>" data-toggle="tooltip" title="Edit Group" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo $group['delete']; ?>" data-toggle="tooltip" title="Delete Group" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
<?php echo $footer; ?> 