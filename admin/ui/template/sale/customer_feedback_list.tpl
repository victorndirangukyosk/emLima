<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
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
        <div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
                </div>		
            </div>
            <div class="panel-body">

                <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
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
                                    <td class="text-left">
                     
                  <?php echo $customer_feedback['customer_name']; ?> 
                       </br>
                  <?php echo $customer_feedback['company_name']; ?>

                                            </td>
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
                                    <td class="text-right">
                                    <div style="width: 100%; display:flex; justify-content: space-between; flex-flow: row wrap; gap: 4px;">
                                               <a href="#" id="accept_feedback" data-feedback-id="<?= $customer_feedback['feedback_id'] ?>" target="_blank" data-toggle="tooltip" title="Accept">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                                </a>  
                                              
                                       </div>
                                    </td>
                    <?php } else if($customer_feedback['rating']<=3  && $customer_feedback['status']=='Attending') { ?>
                  <td class="text-left"><a href="#" id="open_close_feedback" data-feedback-id="<?= $customer_feedback['feedback_id'] ?>" data-toggle="modal" data-dismiss="modal" data-target="#feedbackcloseModal" title="Close Issue" >
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                  </a></td>


                 <?php } else { ?>
                 <td></td>
                  <?php }?>
                 
                 
                   <?php }  ?>
                                        
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php if ($customer_feedbacks) { ?>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php echo $footer; ?>



    
<div class="phoneModal-popup">
        <div class="modal fade" id="feedbackCloseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:385px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>  Close Issue     </h2>
                                          </br> 
                                    </div>
                                    <div id="feedbackCloseModal-message" style="color: red;text-align:center; font-size: 15px;" >
                                    </div>
                                    <div id="feedbackCloseModal-success-message" style="color: green; ; text-align:center; font-size: 15px;">
                                    </div>  
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="feedbackCloseModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label > Closing Comments </label>
                                                        <input id="feedback_id"   name="feedback_id" type="hidden"  class="form-control input-md" required>

                                                    <div class="col-md-12">
                                                        <input id="closing_comments" maxlength="2000" required style="max-width:100% ;" name="closing_comments" type="text" placeholder="CLosing Comments" class="form-control" required>
                                                    <br/> </div>


                                                </div>
                                               </div>
  
                                      <div class="form-row">
                                                <div class="form-group">
                                                    <div class="col-md-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Close</button>


                                                        <button id="fbClose-button" name="fbClose-button" onclick="closeIssue()" type="button" class="btn btn-lg btn-success"  style="width:30%; float: right; margin-top: 10px; height: 45px;border-radius:20px">Save</button>
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

<style>

.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn)
{
 width: 100%;
}
</style>
<script src="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <script type="text/javascript"><!--
  $('.date').datetimepicker({
            pickTime: false
        });

    
        //-->



$('a[id^=\'accept_feedback\']').on('click', function (e) {
e.preventDefault();
console.log($(this).attr("data-feedback-id"));
$.ajax({
                    url: 'index.php?path=sale/customer_feedback/acceptIssue&token=<?php echo $token; ?>&feedback_id='+$(this).attr("data-feedback-id"),
                    type: 'POST',
                    dataType: 'json',
                    data:{ feedback_id: $(this).attr("data-feedback-id") },
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           
                          alert('Issue Accepted');
                          location=location;
                           
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
});

 
$('a[id^=\'open_close_feedback\']').on('click', function (e) {
e.preventDefault();
console.log($(this).attr("data-feedback-id"));
$('#feedbackCloseModal').modal('toggle');
$('#feedbackCloseModal-message').html('');
$('#feedbackCloseModal-success-message').html(''); 

});

</script> 

