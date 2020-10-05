<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-store" data-toggle="tooltip" title="Save" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i><?= $text_testimonials ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
             
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-job_category"><?= $column_job_category ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="job_category" maxlength="100" value="<?php echo $job_category; ?>" placeholder="Enter job category" id="input-job_category" class="form-control" />
                            <?php if ($error_job_category) { ?>
                            <div class="text-danger"><?php echo $error_job_category; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-job_type"><?= $column_job_type ?></label>
                        <div class="col-sm-4">
                         <input type="text" maxlength=100 name="job_type" value="<?php echo $job_type; ?>" placeholder="Enter job type" id="input-job_type" class="form-control" />
                            <?php if ($error_job_type) { ?>
                            <div class="text-danger"><?php echo $error_job_type; ?></div>
                            <?php } ?>

                             </div>
                    </div>
                     <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-job_location"><?= $column_job_location ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="job_location" maxlength="100" value="<?php echo $job_location; ?>" placeholder="Enter job location" id="input-job_category" class="form-control" />
                            <?php if ($error_job_location) { ?>
                            <div class="text-danger"><?php echo $error_job_location; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                      <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-skills"><?= $column_skills ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="skills" maxlength="1500"   value="<?php echo $skills; ?>"  placeholder="Enter skills" id="input-skills" class="form-control"></input>
                            <?php if ($error_skills) { ?>
                            <div class="text-danger"><?php echo $error_skills; ?></div>
                            <?php } ?>
                        </div>
                    </div>


                      <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-experience"><?= $column_experience ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="experience" maxlength=250   placeholder="Enter experience"  value="<?php echo $experience; ?>"  id="input-experience" class="form-control"></input>
                            <?php if ($error_experience) { ?>
                            <div class="text-danger"><?php echo $error_experience; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                      <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-roles_responsibilities"><?= $column_roles_responsibilities ?></label>
                        <div class="col-sm-4">
                            <textarea name="roles_responsibilities"   rows="5" placeholder="Enter roles and responsibilities" id="input-roles_responsibilities" class="form-control"><?php echo $roles_responsibilities; ?></textarea>
                            <?php if ($error_roles_responsibilities) { ?>
                            <div class="text-danger"><?php echo $error_roles_responsibilities; ?></div>
                            <?php } ?>
                        </div>
                    </div>



                      <div class="form-group ">
                        <label class="col-sm-4 control-label" for="input-otherinfo_1"><?= $column_otherinfo_1 ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="otherinfo_1" maxlength=250   value="<?php echo $otherinfo_1; ?>"  placeholder="" id="input-otherinfo_1" class="form-control"></input>
                            <?php if ($error_otherinfo_1) { ?>
                            <div class="text-danger"><?php echo $error_otherinfo_1; ?></div>
                            <?php } ?>
                        </div>
                    </div>


                      <div class="form-group ">
                        <label class="col-sm-4 control-label" for="input-otherinfo_2"><?= $column_otherinfo_2 ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="otherinfo_2" maxlength=250  value="<?php echo $otherinfo_2; ?>"  placeholder="" id="input-otherinfo_2" class="form-control"></input>
                            <?php if ($error_otherinfo_2) { ?>
                            <div class="text-danger"><?php echo $error_otherinfo_2; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                <div class="form-group">
                        <label class="col-sm-4 control-label" for="input-sort_order"><?= $entry_sort_order ?></label>
                        <div class="col-sm-4">
                            <input type="number" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="Enter sort order" id="input-sort_order" class="form-control" />
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-4 control-label" for="input-currency"><?= $entry_status ?></label>
                        <div class="col-sm-4">
                            <select name="status" class="form-control">
                                <?php if ($status==0) { ?>
                                <option value="1" ><?= $text_enable ?></option>
                                <option value="0" selected="selected"><?= $text_disable ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?= $text_enable ?></option>
                                <option value="0"  ><?= $text_disable ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>    
                   
                   
 
                </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript"><!--
    function save(type) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'button';
            input.value = type;
            form = $("form[id^='form-']").append(input);
            form.submit();
        }
        //--></script>

<?php echo $footer; ?>