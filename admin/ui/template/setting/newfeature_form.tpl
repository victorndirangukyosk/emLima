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
                        <label class="col-sm-4 control-label" for="input-name"><?= $entry_name ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Enter name" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                            <div class="text-danger"><?php echo $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-summary"><?= $entry_summary ?></label>
                        <div class="col-sm-4">
                         <input type="text" maxlength=500 name="summary" value="<?php echo $summary; ?>" placeholder="Enter summary" id="input-summary" class="form-control" />
                            <?php if ($error_summary) { ?>
                            <div class="text-danger"><?php echo $error_summary; ?></div>
                            <?php } ?>

                           <!-- <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
                                <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                            </a>
                            <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />-->
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-detail_description"><?= $column_detail_description ?></label>
                        <div class="col-sm-4">
                            <textarea name="detail_description" maxlength=5000 rows="5" placeholder="Enter detail description" id="input-detail_description" class="form-control"><?php echo $detail_description; ?></textarea>
                            <?php if ($error_detail_description) { ?>
                            <div class="text-danger"><?php echo $error_detail_description; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                   
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-business_impact"><?= $column_business_impact ?></label>
                        <div class="col-sm-4">


                         <!-- <label class="radio-multipart">-->
                          <?php if ($business_impact=="Good to have") { ?>
                                <input type="radio" name="business_impact" value="Good to have" checked="checked">Good to have</option><br>
                                <input type="radio"  name="business_impact" value="Needed in future"  >Needed in future</option><br>
                                <input type="radio" name="business_impact" value="Immediate for customer onboarding"  >Immediate for customer onboarding</option>                                
                                <?php } else if ($business_impact=="Needed in future") { ?>
                               <input type="radio"  name="business_impact" value="Good to have" >Good to have</option><br>
                               <input type="radio"  name="business_impact" value="Needed in future"  checked="checked">Needed in future</option><br>
                                <input type="radio" name="business_impact" value="Immediate for customer onboarding"  >Immediate for customer onboarding</option>                             
                               <?php }
                                else if ($business_impact=="Immediate for customer onboarding") { ?>
                                <input type="radio" name="business_impact" value="Good to have" >Good to have</option><br>
                                <input type="radio"  name="business_impact" value="Needed in future"   >Needed in future</option><br>
                               <input type="radio" name="business_impact" value="Immediate for customer onboarding" checked="checked" >Immediate for customer onboarding</option>                           
                                <?php }
                                else   { ?>
                                <input type="radio"  name="business_impact" value="Good to have" checked="checked" >Good to have</option><br>
                                <input type="radio" name="business_impact" value="Needed in future" >Needed in future</option><br>
                               <input type="radio" name="business_impact" value="Immediate for customer onboarding"  >Immediate for customer onboarding</option>                             
                               
                                <?php }
                                 ?>
 
                                       <!-- </label>-->
 
                        </div>
                    </div>   

                     <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-additional_requirement"><?= $column_additional_requirement ?></label>
                        <div class="col-sm-4"><!--type="file"-->
                            <input   name="additional_requirement" value="<?php echo $additional_requirement; ?>" placeholder="" id="input-additional_requirement" class="form-control" />
                          <?php if ($error_file) { ?>
                            <div class="text-danger"><?php echo $error_file; ?></div>
                            <?php } ?>
                        
                        </div>
                    </div>
 <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-is_customer_requirement"><?= $column_is_customer_requirement ?></label>
                        <div class="col-sm-4">


                         <!-- <label class="radio-multipart">-->
                          <?php if ($is_customer_requirement=="1") { ?>
                                <input type="radio"  name="is_customer_requirement" value="Yes" checked="checked">Yes</option><br>
                                <input type="radio" name="is_customer_requirement" value="No"  >No</option><br>
                                 <?php }
                                   else if($is_customer_requirement=="yes")  { ?>
                                <input type="radio" name="is_customer_requirement" value="Yes" checked="checked" >Yes</option><br>
                                <input type="radio" name="is_customer_requirement" value="No"  >No</option><br>
                               
                                <?php }
                                else   { ?>
                                <input type="radio" name="is_customer_requirement" value="Yes" >Yes</option><br>
                                <input type="radio" name="is_customer_requirement"  value="No" checked="checked">No</option><br>
                               
                                <?php }
                                 ?>
 
                                       <!-- </label>-->
 
                        </div>
                    </div>   


  <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-customer_name"><?= $column_customer_name ?></label>
                        <div class="col-sm-4">
                            <input type="text" maxlength=50 name="customer_name" value="<?php echo $customer_name; ?>" placeholder="" id="input-customer_name" class="form-control" />
                        
                        
                         <?php if ($error_customer_name) { ?>
                            <div class="text-danger"><?php echo $error_customer_name; ?></div>
                            <?php } ?></div>
                    </div>


                     <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-no_of_customers_requested"><?= $column_no_of_customers_requested ?></label>
                        <div class="col-sm-4">
                            <input type="number" maxlength=50  name="no_of_customers_requested" value="<?php echo $no_of_customers_requested; ?>" placeholder="" id="input-no_of_customers_requested" class="form-control" />
                        
                        
                         <?php if ($error_no_of_customers_requested) { ?>
                            <div class="text-danger"><?php echo $error_no_of_customers_requested; ?></div>
                            <?php } ?></div>
                    </div>


                     <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-no_of_customers_onboarded"><?= $column_no_of_customers_onboarded ?></label>
                        <div class="col-sm-4">
                            <input type="text" maxlength=50 name="no_of_customers_onboarded" value="<?php echo $no_of_customers_onboarded; ?>" placeholder="" id="input-no_of_customers_onboarded" class="form-control" />
                       
                        <?php if ($error_no_of_customers_onboarded) { ?>
                            <div class="text-danger"><?php echo $error_no_of_customers_onboarded; ?></div>
                            <?php } ?>
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