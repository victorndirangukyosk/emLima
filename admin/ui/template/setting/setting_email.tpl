<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
                          </ul>
                    <div class="tab-content">
                              <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-consolidatedorder">Consolidated Orders</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_consolidatedorder" value="<?php echo $config_consolidatedorder; ?>" placeholder="Email ID" id="input-consolidatedorder" class="form-control" />
                                    <?php if ($error_consolidatedorder) { ?>
                                    <div class="text-danger"><?php echo $error_consolidatedorder; ?></div>
                                    <?php } ?>
                                </div>
                            </div> 

                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-careers">Careers</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_careers" value="<?php echo $config_careers; ?>" placeholder="Email ID" id="input-careers" class="form-control" />
                                    <?php if ($error_careers) { ?>
                                    <div class="text-danger"><?php echo $error_careers; ?></div>
                                    <?php } ?>
                                </div>
                            </div> 

                             <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-stockout">Stock Out</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_stockout" value="<?php echo $config_stockout; ?>" placeholder="Email ID" id="input-stockout" class="form-control" />
                                    <?php if ($error_stockout) { ?>
                                    <div class="text-danger"><?php echo $error_stockout; ?></div>
                                    <?php } ?>
                                </div>
                            </div> 


                             <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-issue">Feedback/Issue</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_issue" value="<?php echo $config_issue; ?>" placeholder="Email ID" id="input-issue" class="form-control" />
                                    <?php if ($error_issue) { ?>
                                    <div class="text-danger"><?php echo $error_issue; ?></div>
                                    <?php } ?>
                                </div>
                            </div> 


                              <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-financeteam">Finance Team</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_financeteam" value="<?php echo $config_financeteam; ?>" placeholder="Email ID" id="input-financeteam" class="form-control" />
                                    <?php if ($error_financeteam) { ?>
                                    <div class="text-danger"><?php echo $error_financeteam; ?></div>
                                    <?php } ?>
                                </div>
                            </div> 


                              <div class="form-group required">
                                <label class="col-sm-2 control-label" for="input-meatcheckingteam">Meat Checking Team</label>
                                <div class="col-sm-10">
                                    <input type="text" name="config_meatcheckingteam" value="<?php echo $config_meatcheckingteam; ?>" placeholder="Email ID" id="input-meatcheckingteam" class="form-control" />
                                    <?php if ($error_meatcheckingteam) { ?>
                                    <div class="text-danger"><?php echo $error_meatcheckingteam; ?></div>
                                    <?php } ?>
                                </div>
                            </div> 


                      </div>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>
  <script type="text/javascript"> 
     </script> 

</div>
 
  
            
<?php echo $footer; ?>