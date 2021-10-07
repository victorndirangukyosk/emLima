<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left">Order ID</td>
        <td class="text-left">Activity</td>
        <td class="text-left">IP</td>
        <td class="text-left">Activity Date</td>
        <td class="text-left">Activity By</td>
        <td class="text-left">Action</td>


      </tr>
    </thead>
    <tbody>
      <?php if ($activities) { ?>
      <?php foreach ($activities as $activity) { ?>
      <tr>
        <td class="text-left"><?php echo $activity['order_id']; ?></td>
        <td class="text-left"><?php echo $activity['comment']; ?></td>
        <td class="text-left"><?php echo $activity['ip']; ?></td>
        <td class="text-left"><?php echo $activity['date_added']; ?></td>
        <td class="text-left"><?php echo $activity['user']; ?></td>
        <td class="text-left">
         <?php if ($activity['order_id']!=null && $activity['order_id']>0) { ?>
          <a href="#" onclick="getOrderInfo(<?= $activity['order_id'] ?>)" data-toggle="modal" data-dismiss="modal" data-target="#orderInfoModal" title="Order Details">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                       </a>
                                        <?php } ?>
        </td>




      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>




<div class="phoneModal-popup">
        <div class="modal fade" id="orderInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content"  >
                    <div class="modal-body"  style="height:500px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <div class="store-find-block">
                            <div class="mydivsss">
                                <div class="store-find">
                                    <div class="store-head">
                                        <h2>  Order Details     </h2>
                                          
                                    </div>
                                    
                                      </br>
                                    <!-- Text input-->
                                    <div class="store-form">
                                        <form id="orderInfoModal-form" action="" method="post" enctype="multipart/form-data">
 

                                            
                                                <div class="form-group">
                                                 <div class="col-sm-4 control-label">
                                                    <label > Order ID </label>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <input id="order_id"  style="max-width:100% ;" name="order_id" readonly class="form-control input-md" >
                                                   
                                                    </div>
                                             </div>
                                               


                                             
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label"   > Order Status </label>

                                                    <div class="col-sm-6">
                                                        <input id="order_status" readonly style="max-width:100% ;" name="order_status" type="text"  class="form-control input-md" >
                                                         </div>

                                                   
                                                </div>


                                                 
                                                <div class="form-group">
                                                    <label  class="col-sm-4 control-label"   > Order Total </label>

                                                    <div class="col-sm-6">
                                                        <input id="total" readonly style="max-width:100% ;" name="total" type="text"  class="form-control input-md" >
                                                     </div>

                                                   
                                                </div>

                                                   
                                                <div class="form-group">
                                                    <label  class="col-sm-4 control-label"   > Date Added</label>

                                                    <div class="col-sm-6">
                                                        <input id="date_added" readonly style="max-width:100% ;" name="date_added" type="text"  class="form-control input-md" >
                                                     </div>

                                                   
                                                </div>  
                                                  

                                                     
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label"    > Delivery Date </label>

                                                    <div class="col-sm-6">
                                                        <input id="delivery_date" readonly style="max-width:100% ;" name="delivery_date" type="text"  class="form-control input-md" >
                                                    </div>

                                                   
                                                </div>


                                                  
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label"    > Payment Method </label>

                                                    <div class="col-sm-6">
                                                        <input id="payment_method" readonly style="max-width:100% ;" name="payment_method" type="text"  class="form-control input-md" >
                                                     </div>

                                                   
                                                </div>


                                                
                                                <div class="form-group">
                                                    <label  class="col-sm-4 control-label"   > Payment Status </label>

                                                    <div class="col-sm-6">
                                                        <input id="payment_status" readonly style="max-width:100% ;" name="payment_status" type="text"  class="form-control input-md" >
                                                     </div>

                                                   
                                                </div> 

                                                <div class="form-group">
                                                    <div class="col-sm-12"> 
                                                        <button type="button" class="btn btn-grey" data-dismiss="modal" style="width:20%;background: #47a947;float: right;margin-top: 10px;height: 30px;border-radius:20px;">Close</button>


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


function getOrderInfo($order_id) {
               
             

                 $.ajax({
                    url: 'index.php?path=sale/order/getOrderInfo&token=<?php echo $token; ?>&order_id='+$order_id,
                    type: 'POST',
                    dataType: 'json',
                    data:{order_id:$order_id},
                    async: true,
                    success: function(json) {
                        console.log(json); 
                        if (json['status']) {
                           $('input[name="order_id"]').val(json['order_id']) ;
                           $('input[name="date_added"]').val(json['date_added']) ;
                           $('input[name="delivery_date"]').val(json['delivery_date']) ;
                           $('input[name="total"]').val(json['total']) ;
                           $('input[name="order_status"]').val(json['status']) ;
                           $('input[name="payment_method"]').val(json['payment_method']) ;
                           $('input[name="payment_status"]').val(json['paid']) ;
                        }
                        else {
                             $('input[name="order_id"]').val('') ;
                           $('input[name="date_added"]').val('') ;
                           $('input[name="total"]').val('') ;
                           $('input[name="order_status"]').val('') ;
                           $('input[name="payment_method"]').val('') ;
                           $('input[name="payment_status"]').val('') ;
                           $('input[name="delivery_date"]').val('') ;
                            
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) { 

                        $('input[name="order_id"]').val('') ;
                           $('input[name="date_added"]').val('') ;
                           $('input[name="total"]').val('') ;
                           $('input[name="order_status"]').val('') ;
                           $('input[name="payment_method"]').val('') ;
                           $('input[name="delivery_date"]').val('') ;
                           $('input[name="payment_status"]').val('') ;
                                
                                    return false;
                                }
                });


               
               $('input[name="order_id"]').val($order_id) ;
                  
            }

</script>
