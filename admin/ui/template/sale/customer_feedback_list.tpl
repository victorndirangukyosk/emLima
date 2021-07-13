<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form   method="post" enctype="multipart/form-data"   id="form-customer-feedback">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>-->
                  <!--<td class="text-left"><?php echo $column_Company; ?></td>-->
                  <td class="text-left"><?php echo $column_Customer; ?></td>
                 
                  <td class="text-left"><?php echo $column_rating; ?></td>
                  
                  <td class="text-left"><?php echo $column_feedback_type; ?></td>
                  <td class="text-left"><?php echo $column_comments; ?></td>
                  <td class="text-left">Order_Id</td>
                  <td class="text-left">Raised On</td>

                  <td class="text-left">Status</td>
                  <td class="text-left">Accepted By</td>
                  <td class="text-left">Closed Date</td>
                  <td class="text-left">Closed Comments</td>
                   <?php if ($this->user->isCustomerExperience()){ ?>
                   <td class="text-left">Action</td>  <?php } ?>
                    

                 
                </tr>
              </thead>
              <tbody>
                <?php if ($customer_feedbacks) { ?>
                <?php foreach ($customer_feedbacks as $customer_feedback) { ?>
                <tr>
                  <!--<td class="text-center"><?php if (in_array($customer_feedback['feedback_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer_feedback['feedback_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer_feedback['feedback_id']; ?>" />
                    <?php } ?></td>-->
                  <td class="text-left">
                     
                  <?php echo $customer_feedback['customer_name']; ?> 
                       </br>
                  <?php echo $customer_feedback['company_name']; ?>

                                            </td>
                  <!--<td class="text-left"><?php echo $customer_feedback['customer_name']; ?></td>-->

                  <td class="text-left"><?php echo $customer_feedback['rating']; ?></td>

                  <td class="text-left" style="width:100px"><?php  echo $customer_feedback['feedback_type']; ?>  </td>
                  <td class="text-left" style="width:200px"><?php  echo $customer_feedback['comments']; ?> </td>
                  <td class="text-left"><?php echo $customer_feedback['order_id']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['created_date']; ?></td>
                  
                  <td class="text-left"><?php echo $customer_feedback['status']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['Accepted_user']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['closed_date']; ?></td>
                  <td class="text-left"><?php echo $customer_feedback['closed_comments']; ?></td>
                 
                 <?php if (!$this->user->isCustomerExperience() ){ ?>
                  <?php if ($customer_feedback['rating']<=3  && $customer_feedback['status']=='Open') { ?>
                  <td class="text-center"><button class="btn btn-primary" onclick="return AcceptIssue(<?= $customer_feedback['feedback_id'] ?>)" style="background:#f56b6b">Accept</button>
                                  
                  </td>
 

 
                <?php } else if($customer_feedback['rating']<=3  && $customer_feedback['status']=='Attending'){ ?>
                  <td class="text-left"><button class="" style="background:green;width:65px"  data-toggle="modal" data-dismiss="modal" data-target="#feedbackcloseModal" title="PO Details">Close</button></td>


                 <?php }else {?>
                 <td></td>
                  <?php }?>
                 
                 
                   <?php }  ?>

                 
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 



 
<div class="phoneModal-popup">
        <div class="modal fade" id="feedbackcloseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:385px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>  Close Issue    </h2>
                                          </br> 
                                    </div>
                                    <div id="feedbackcloseModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="feedbackcloseModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="feedbackcloseModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label > Closing Details </label>
                                                        <input id="feedback_id"   name="feedback_id" type="hidden"  class="form-control input-md" required>

                                                    <div class="col-md-12">
                                                        <textarea id="closing_comments" maxlength="2000" required style="max-width:100% ;" name="closing_comments" type="text" placeholder="Closing Comments" class="form-control" required>
                                                    <br/> </div>


                                                </div>
                                               
 


                                                 <div class="form-group">
                                                    <div class="col-md-12">
                                                       </br>
                                                     
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Close</button>


                                                        <button id="po-button" name="po-button" onclick="savePO()" type="button" class="btn btn-lg btn-success"  style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>  
                                </div>
                            </div>
                           
                            <!-- next div code -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    

<script  type="text/javascript">
 
 

function AcceptIssue($feedback_id) {
               
               // $('#poModal-message').html('');
               //$('#poModal-success-message').html('');
                 
              alert(1);
                 $.ajax({
                    url: 'index.php?path=sale/customer_feedback/acceptIssue&token=<?php echo $token; ?>&feedback_id='+$feedback_id,
                    type: 'POST',
                    dataType: 'json',
                    data:{feedback_id:$feedback_id},
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           //$('input[name="po_number"]').val(json['po_number']) ;
                          alert('Issue Accepted');
                           
                        }
                        else {
                            // $('input[name="po_number"]').val('') ;
                           
                            
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) { 

                       //  $('input[name="po_number"]').val('') ;
                                 
                                    return false;
                                }
                });

 
                  
            }





function CloseIssue($feedback_id) {
               
               // $('#poModal-message').html('');
               //$('#poModal-success-message').html('');
                 

                 $.ajax({
                    url: 'index.php?path=sale/customer/closeIssue&token=<?php echo $token; ?>&feedback_id='+$feedback_id,
                    type: 'POST',
                    dataType: 'json',
                    data:{feedback_id:$feedback_id},
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           //$('input[name="po_number"]').val(json['po_number']) ;
                           
                        }
                        else {
                            // $('input[name="po_number"]').val('') ;
                           
                            
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) { 

                       //  $('input[name="po_number"]').val('') ;
                                 
                                    return false;
                                }
                });

 
                  
            }

</script>