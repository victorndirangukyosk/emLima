<?php echo $header; ?>
<div id="workingMsgHiddenDiv" style="display: none;">Please wait, we are preparing your cart...</div>
<div class="col-md-9 nopl">
    <div class="dashboard-profile-content">
        <div class="my-order">
            <?php if ($orders) { ?>
            <div class="order-details">
                <?php foreach ($orders as $order) { ?>

                <div class="list-group my-order-group">
                    <li class="list-group-item my-order-list-headnew">
                        <div class="row"><div class="col-md-2" align="left"><strong>#<?php echo $order['order_id']; ?></strong></div><div class="col-md-4" align="right">Store Name: <span><?php echo $order['store_name']; ?></div><div class="col-md-6" align="right">Order Date: <span><?php echo $order['date_added']; ?>, <?php echo $order['time_added']; ?></div></div>
                            <!--<div class="pull-right">
                                <button type="button" style="height:25px" onclick="excel( <?=$order["order_id"] ?>,'<?=$order["order_company"] ?>');" data-toggle="tooltip" title="Download Ordered Products"
                                        class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
                            </div>-->



                            <!--<div class="pull-right">
                            <?php if($order['status'] == 'Delivered' || $order['status'] == 'Cancelled' ) { ?>
                              <a data-confirm="Available products  in this order will be added to cart !!" id="additemstocart" class="btn btn-success download" style="margin-right: 4px !important; height: 27px;margin-left:4px;"
                          data-store-id="<?= $order['store_id']; ?>" data-toggle="tooltip"
                          value="<?php echo $order['order_id']; ?>" title="Add To Cart/Reorder"><i
                            class="fa fa-cart-plus"></i></a><?php } ?>
                            </div> --> 

                            <!--<?php if($order['customer_id'] == $this->customer->getId() && $order['edit_own_order'] != NULL) { ?>
                            <a href="<?php echo $order['edit_own_order'];?>" class="btn btn-success" title="Edit Your Order" style="margin-right: 4px !important; height: 27px;margin-left:4px;"><i class="fa fa-edit"></i></a>
                            <?php } ?>-->


                            <!--<?php if($order['status'] == 'Arrived for Delivery'){?>
                                                     <a href="<?php echo $order['accept_reject_href']?>"  class="btn btn-default btn-xs btn-accept-reject" >Accept Delivery</a>
                                                    <?php } ?>-->


                            
                            <!--<a href="#" data-toggle="modal" data-target="#contactusModal"  class="btn btn-default btn-xs"><?= $text_report_issue ?></a>

                            <?php if($order['status'] == 'Order Recieved' || $order['status'] == 'Order Approval Pending' ){?>
                            <a href="#" id="cancelOrder" data-id='<?=$order["order_id"] ?>' style="margin-right: 4px !important; height: 27px;margin-left:4px;" class="btn btn-danger btn-xs btn-custom-remove"><?= $text_cancel ?></a> 
                                                    <?php } ?>-->
                            


                        </span>
                    </li>
                    <li class="list-group-item">
                        <div class="my-order-block">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="my-order-delivery">
                                        <h3 class="my-order-title label" id="orderstatus<?= $order['order_id']; ?>" style="width:75%;border: 1px solid #<?= $order['order_status_color']; ?>;color: #<?= $order['order_status_color']; ?>;display: block;line-height: 2; text-align: center;"><?php echo $order['status']; ?></h3>

                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11" viewBox="0 0 20 21" fill="none">
                                            <path d="M10 18.5001C8.14348 18.5001 6.36301 17.7626 5.05025 16.4499C3.7375 15.1371 3 13.3566 3 11.5001C3 9.64359 3.7375 7.86311 5.05025 6.55036C6.36301 5.23761 8.14348 4.50011 10 4.50011C11.8565 4.50011 13.637 5.23761 14.9497 6.55036C16.2625 7.86311 17 9.64359 17 11.5001C17 13.3566 16.2625 15.1371 14.9497 16.4499C13.637 17.7626 11.8565 18.5001 10 18.5001ZM10 2.50011C7.61305 2.50011 5.32387 3.44832 3.63604 5.13615C1.94821 6.82397 1 9.11316 1 11.5001C1 13.8871 1.94821 16.1762 3.63604 17.8641C5.32387 19.5519 7.61305 20.5001 10 20.5001C12.3869 20.5001 14.6761 19.5519 16.364 17.8641C18.0518 16.1762 19 13.8871 19 11.5001C19 9.11316 18.0518 6.82397 16.364 5.13615C14.6761 3.44832 12.3869 2.50011 10 2.50011ZM10.5 6.50011H9V12.5001L13.75 15.3501L14.5 14.1201L10.5 11.7501V6.50011ZM5.88 1.89011L4.6 0.360107L0 4.21011L1.29 5.74011L5.88 1.89011ZM20 4.22011L15.4 0.360107L14.11 1.89011L18.71 5.75011L20 4.22011Z" fill="#0077CD"/>
                                            </svg>
                                                <span class="my-order-date">ETA: <?php echo $order['eta_date']; ?>, <?php echo $order['eta_time']; ?></span>
                                                                            
                                  </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="my-order-info">
                                        <h3 class="my-order-title"><?php if($order['order_company'] == NULL) { echo $order['store_name']; ?> (<?php echo $order['name']; ?>) <?php } else { echo $order['order_company']; } ?> - <?php echo $order['total']; ?></h3>

                                        <?php if($order['realproducts']) { ?>
                                        <span class="my-order-id">Total Items: <?php echo $order['real_products']; ?></span>

                                        <?php } else { ?>
                                        <span class="my-order-id">Total Items:  <?php echo $order['products']; ?></span>
                                        
                                        <?php } ?>

                                         <br>

                                        <span class="my-order-id">Mode of payment:  <?php echo $order['payment_method']; ?></span>



                                    </div>
                                </div>
                                <!--<div class="col-md-3"><a href="<?php echo $order['href']; ?>" class="btn-link text_green"><?= $text_view?> <?php echo $order['products']; ?> <?= $text_items_ordered?> </a>-->
                                <!--<div class="col-md-3"><a href="<?php echo $order['href']; ?>" class="btn-link text_green"><?= $text_view?> <?php echo $order['productss']; ?> <?= $text_items_ordered?> </a>-->
                                <!-- <div class="col-md-3"><a href="#"  data-toggle="modal" data-target="#viewProductsModal" onclick="viewProductsModal(('<?php echo $order['order_id']; ?>'));" class="btn-link text_green"><?= $text_view?>  <?php echo $order['productss']; ?> <?= $text_items_ordered?> </a>   -->
                                <div class="col-md-3">
                                
                <select name="select"  id="action_id_<?php echo $order['order_id']; ?>" class="form-control newddl" style='height: 45px; font-family:Arial, FontAwesome;'>
                                <option   value="<?=$order["order_id"] ?>" type="Select Option" order-id="<?=$order["order_id"] ?>"  selected="selected">&#xf0ca; &nbsp;Select Option</option>
                                <option disabled style="height: 1px !important;" ></option>
                                <option   value="<?=$order["order_id"] ?>" type="Download cart" order-id="<?=$order["order_id"] ?>"  order_company="<?=$order["order_company"] ?>"  >&#xf019; &nbsp;Download cart</option>
                                <option disabled style="height: 1px !important;" ></option>
                            <?php if($order['customer_id'] == $this->customer->getId() && $order['edit_own_order'] != NULL) { ?>
                                
                                <option   value="<?=$order["order_id"] ?>" type="Edit order"   order-id="<?=$order["order_id"] ?>"  order_href="<?php echo $order['edit_own_order'];?>">&#xf044; &nbsp;Edit Order</option>
                                <option disabled style="height: 1px !important;" ></option>
                            
                                        <?php } ?>                
                                <?php if($order['status'] == 'Order Recieved' || $order['status'] == 'Order Approval Pending' ){?>
                                <option   value="<?=$order["order_id"] ?>" type="Cancel order"   order-id="<?=$order["order_id"] ?>" >&#xf05e; &nbsp;Cancel order</option>      
                                <option disabled style="height: 1px !important;" ></option>
                                  <?php } ?>  
                                <option   value="<?=$order["order_id"] ?>" type="Report issue"   order-id="<?=$order["order_id"] ?>">&#xf071; &nbsp;Report Issue</option>
                                <option disabled style="height: 1px !important;" ></option>
                                <option   value="<?=$order["order_id"] ?>" type="View items"   order-id="<?=$order["order_id"] ?>">&#xf07a; &nbsp;View Items Ordered</option>
                                <option disabled style="height: 1px !important;" ></option>
                                     <?php if($order['realproductss']) { ?>
                                <option   value="<?=$order["order_id"] ?>" type="View real items"  view_href="<?php echo $order['real_href']; ?>"  order-id="<?=$order["order_id"] ?>"  >&#xf217; &nbsp;View Real Items</option>
                                <option disabled style="height: 1px !important;" ></option>
                                        <?php } ?>    


                                     <?php if($order['status'] == 'Arrived for Delivery'){?>
                                                  
                                <option  value="<?=$order["order_id"] ?>" type="Accept delivery" accept_reject_href="<?php echo $order['accept_reject_href']; ?>"  order-id="<?=$order["order_id"] ?>" >&#xf058; &nbsp;Accept delivery</option>
                                <option disabled style="height: 1px !important;" ></option>
                                                   
                                                 
                                                    <?php } ?>
                             

                             
                              <?php if($order['status'] == 'Delivered' || $order['status'] == 'Cancelled' || $order['status'] == 'In Transit') { ?>
                              <option   value="<?=$order["order_id"] ?>" type="Add to cart"  data-store-id="<?= $order['store_id']; ?>"  order-id="<?=$order["order_id"] ?>" >&#xf291; &nbsp;Add to cart</option>      
                              <option disabled style="height: 1px !important;" ></option>
                              <?php } ?>
                              <?php if($order['status'] == 'Delivered' || $order['status'] == 'In Transit') { ?>
                              <option value="<?=$order["order_id"] ?>" type="Report missed products"  view_href=""  order-id="<?=$order["order_id"] ?>"  >&#xf179; &nbsp;Report Missed Products</option>
                              <option disabled style="height: 1px !important;" ></option>
                              <option value="<?=$order["order_id"] ?>" type="Report rejected products"  view_href=""  order-id="<?=$order["order_id"] ?>"  >&#xf179; &nbsp;Report Rejected Products</option>
                              <?php } ?>
                               </select>
                                <br/>

                                                                        
                                      <!--<?php if($order['realproductss']) { ?>
                                         <a href="<?php echo $order['real_href']; ?>" class="btn-link text_green"><?= $text_view?> <?php echo $order['realproductss']; ?> <?= $text_real_items_ordered?> </a>
                                      <?php } ?> -->


                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="my-order-details" style="border: none !important;">
                            <div class="row">
                                <div class="col-md-5"><?= $text_delivery_address?></div>
                                <?php if(isset($order['shipping_address'])) { ?>
                                <div class="col-md-4">
                                    <?= $order['shipping_address']['address'] ?>,<?= $order['shipping_address']['city'] ?>,<?= $order['shipping_address']['zipcode'] ?></div>
                                <?php } else { ?>
                                <div class="col-md-4"> </div>
                                <?php } ?>
                                <div class="col-md-3">  
                            <a class="btn-link text_green" role="button" data-toggle="collapse" href="#<?= $order['order_id'] ?>" aria-expanded="false" aria-controls="<?= $order['order_id'] ?>">View Billing Details</a> </div>
 
                            </div>

                            <div class="row">
                                
                                <?php if ($order['payment_method'] == 'mPesa Online'){
                                if(!empty($order['payment_transaction_id'])){
                                $payment_status = '<span style="color:green">Success</span>';
                                }else{
                                $payment_status = '<span style="color:red">Failed</span>';
                                }

                                if($order['status'] == 'Cancelled'){
                                $payment_status = '<span style="color:#'.$order['order_status_color'].'">Cancelled</span>';
                                }
                                ?>
                                <div class="col-md-5">Payment Status</div>
                                <div class="col-md-4"><?php echo $payment_status; ?></div>
                                <?php if(!empty($order['payment_transaction_id'])){?>
																<div class="col-md-5">Transaction Id</div>
																<div class="col-md-6"><?php echo $order['payment_transaction_id']; ?></div>
	
																<?php } ?>
                                <?php } ?>

                                <?php if ($order['payment_method'] == 'Wallet Payment'){
                                if(!empty($order['payment_transaction_id']) && $order['paid']=='Y'){
                                $payment_status = '<span style="color:green">Paid</span>';
                                }
                                else if($order['paid']=='P'){
                                $payment_status = '<span style="color:orange">Partially Paid</span>';
                                }
                                else{
                                $payment_status = '<span style="color:red">Not Paid</span>';
                                }

                                if($order['status'] == 'Cancelled'){
                                $payment_status = '<span style="color:#'.$order['order_status_color'].'">Cancelled</span>';
                                }
                                ?>
                                <div class="col-md-5">Payment Status</div>
                                <div class="col-md-4"><?php echo $payment_status; ?></div>
                                <?php if(!empty($order['payment_transaction_id'])){?>
																<div class="col-md-5">Transaction Id</div>
																<div class="col-md-6"><?php echo $order['payment_transaction_id']; ?></div>
	
																<?php } ?>
                                <?php } ?>

                            </div>   
                        </div>
                    </li>
                    <?php if($order['parent_details'] != NULL /*&& !empty($_SESSION['parent']) && $_SESSION['parent'] > 0*/) { ?>
                    <li class="list-group-item">
                        <div class="my-order-details" style="border: none !important;">
                            <?php if($order['parent_details'] != NULL /*&& !empty($_SESSION['parent']) && $_SESSION['parent'] > 0*/) { ?>
                            <div class="row">
                                <div class="col-md-5">Parent User Email</div>
                                <div class="col-md-4"><?php echo $order['parent_details']; ?></div>
                                <div class="col-md-2 text-right"><?php echo $order['parent_approval']; ?></div>
                            </div>
                            <?php if($order['sub_user_order'] == TRUE) { ?>
                            <?php if($order['head_chef_email'] != NULL) { ?>
                            <div class="row">
                                <div class="col-md-5">First Level Approver</div>
                                <div class="col-md-4"><?php echo $order['head_chef_email']; ?></div>
                                <div class="col-md-2 text-right"><?php echo $order['head_chef']; ?></div>
                            </div>
                            <?php } ?>
                            <?php if($order['procurement_person_email'] != NULL) { ?>
                            <div class="row">
                                <div class="col-md-5">Second Level Approver</div>
                                <div class="col-md-4"><?php echo $order['procurement_person_email']; ?></div>
                                <div class="col-md-2 text-right"><?php echo $order['procurement']; ?></div>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </li>
                    <?php } ?>
                    <li class="list-group-item my-order-details-block">
                        <div class="collapse" id="<?= $order['order_id'] ?>">
                            <div class="my-order-details">
                                <div class="row">
                                    <div class="col-md-5"><?= $text_payment ?></div>
                                    <div class="col-md-6">
                                        <div class="">
                                            <?= $order['order_total'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> 
                   <!-- <li class="list-group-item">
                        <div class="my-order-showaddress">  
                            <a class="btn-link text_green" role="button" data-toggle="collapse" href="#<?= $order['order_id'] ?>" aria-expanded="false" aria-controls="<?= $order['order_id'] ?>">View Billing Details</a>&nbsp;|&nbsp;<a class="btn-link text_green" role="button" href="<?php echo ($order['realproducts'] ? $order['real_href'] : $order['href'].'&order_status='.urlencode($order['status'])) ;?>" aria-expanded="false" aria-controls="<?= $order['status'] ?>"><?= $text_view_order?></a>
                            <?php if($order['edit_order'] != NULL && (($order['order_approval_access_role'] == 'head_chef' && $order['head_chef'] == 'Pending') || ($order['order_approval_access_role'] == 'procurement_person' && $order['procurement'] == 'Pending') || (empty($_SESSION['parent']) && $order['parent_approval'] == 'Pending'))) { ?> |&nbsp;<a class="btn-link text_green" role="button" href="<?php echo $order['edit_order']; ?>" id="editorder<?php echo $order['order_id']; ?>" aria-expanded="false">Edit Order</a> <?php } ?>
                        </div>
                    </li>-->

                            <?php if($order['edit_order'] != NULL && (($order['order_approval_access_role'] == 'head_chef' && $order['head_chef'] == 'Pending') || ($order['order_approval_access_role'] == 'procurement_person' && $order['procurement'] == 'Pending') || (empty($_SESSION['parent']) && $order['parent_approval'] == 'Pending'))) { ?>
                            <li class="list-group-item"> <div class="my-order-showaddress"><a class="btn-link text_green" role="button" href="<?php echo $order['edit_order']; ?>" id="editorder<?php echo $order['order_id']; ?>" aria-expanded="false">Edit Order</a> </div>
                    </li> <?php } ?>



                    <?php if($order['sub_user_order'] == TRUE && $order['parent_approval'] == 'Pending' && $order['head_chef'] == 'Pending' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'head_chef') { ?>
                    <li class="list-group-item">
                        <div class="my-order-showaddress" id="<?php echo $order['order_id']; ?>">  
                            <a href="#" id="approve_order_head_chef" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn-newapprove">APPROVE ORDER</a>
                            <a href="#" id="reject_order_head_chef" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn-newreject">REJECT ORDER</a>
                        </div>
                    </li>
                    <?php } else if($order['sub_user_order'] == TRUE && $order['head_chef'] == 'Approved' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'head_chef') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['head_chef']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } else if($order['sub_user_order'] == TRUE && $order['head_chef'] == 'Rejected' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'head_chef') { ?> 
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #FF5C23;display: block;line-height: 2; text-align:center;"><?php echo $order['head_chef']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } ?>

                    <?php if($order['sub_user_order'] == TRUE && $order['head_chef'] == 'Approved' && $order['procurement'] == 'Pending' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'procurement_person') { ?>
                    <li class="list-group-item">
                        <div class="my-order-showaddress" id="<?php echo $order['order_id']; ?>">  
                            <a href="#" id="approve_order_procurement" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn-newapprove">APPROVE ORDER</a>
                            <a href="#" id="reject_order_procurement" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn-newreject">REJECT ORDER</a>
                        </div>
                    </li>
                    <?php } else if($order['sub_user_order'] == TRUE && $order['procurement'] == 'Approved' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'procurement_person') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['procurement']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } else if($order['sub_user_order'] == TRUE && $order['procurement'] == 'Rejected' && $order['order_approval_access'] == true && $order['order_approval_access_role'] == 'procurement_person') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #FF5C23;display: block;line-height: 2; text-align:center;"><?php echo $order['procurement']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if($order['sub_user_order'] == TRUE && $order['status'] == 'Order Approval Pending' && $order['parent_approve_order'] == 'Need Approval' && $order['parent_approval'] == 'Pending') { ?>
                    <li class="list-group-item">
                        <div class="my-order-showaddress" id="<?php echo $order['order_id']; ?>" style="padding: 18px;">  
                            <a href="#" id="approve_order" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn-newapprove">APPROVE ORDER</a>
                            <a href="#" id="reject_order" data-id="<?= $order['order_id'] ?>" data-custid="<?= $order['customer_id'] ?>" class="btn-newreject">REJECT ORDER</a>
                        
                        </div>
                    </li>
                    <?php } elseif($order['sub_user_order'] == TRUE && $order['parent_approve_order'] == 'Need Approval' && $order['parent_approval'] != 'Pending') { ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="my-order-showaddress">  
                                    <h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;"><?php echo $order['parent_approval']; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                   <!-- <li class="list-group-item">
                        <div class="my-order-refund" style="font-size:13px;">
                            <i class="fa fa-money"></i> <span><?= $text_refund_text_part1 ?> <strong><?= $text_refund_text_part2 ?> </strong> <?= $text_refund_text_part3 ?></span>
                        </div>
                    </li>-->
                </div>
                <?php } ?>
                <div class="text-right" style='display: none;'>
                    <?php echo $pagination; ?>
                </div>
            </div>

            <?php if(!empty($pagination)) { ?>
            <div id="button-area">
                <button class="load_more btn btn-default center-block" type="button">
                    <span class="load-more-text"><?= $text_load_more?></span>
                    <div class="load-more-loader" style="display: none;"></div>
                </button>    
            </div>
            <?php } ?>

            <?php } else { ?>
            <p><?php echo $text_empty; ?></p>
            <?php } ?>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<?php echo $footer; ?>



  <div class="editAddressModal">
        <div class="modal fade" id="viewProductsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog a" role="document" style="top:80px;right:160px;">
                <div class="modal-content" style="width:170%">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                           <div class="col-md-12">
                                <h2>Order Details</h2>
                                </div>
                            

                            <div class="order-details-form-panel">
                                    <!--  form here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="editAddressModal">
        <div class="modal fade" id="viewMissedProductsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog a" role="document" style="top:80px;right:160px;">
                <div class="modal-content" style="width:170%">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                           <div class="col-md-12">
                           <h2>Missed Products</h2>
                           </div>
                            
                            <div class="missingproducts-details-form-panel">
                                    <!--  form here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="editAddressModal">
        <div class="modal fade" id="viewRejectedProductsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog a" role="document" style="top:80px;right:160px;">
                <div class="modal-content" style="width:170%">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                           <div class="col-md-12">
                           <h2>Rejected Products</h2>
                           </div>
                            
                            <div class="rejectedproducts-details-form-panel">
                                    <!--  form here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?= $base; ?>front/ui/theme/mvgv2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

<script type="text/javascript" src="<?= $base; ?>front/ui/javascript/jquery/infinitescroll/jquery.infinitescroll.min.js" ></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/javascript/jquery/infinitescroll/manual-trigger.js" ></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/html5lightbox.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/jquery.sticky.min.js"></script>
<script type="text/javascript" src="<?= $base; ?>front/ui/theme/mvgv2/js/header-sticky.js"></script>

<?php if ($kondutoStatus) { ?>

<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

                                    var __kdt = __kdt || [];

                                    var public_key = '<?php echo $konduto_public_key ?>';

                                    console.log("public_key");
                                    console.log(public_key);
                                    __kdt.push({"public_key": public_key}); // The public key identifies your store
                                    __kdt.push({"post_on_load": false});
                                    (function () {
                                        var kdt = document.createElement('script');
                                        kdt.id = 'kdtjs';
                                        kdt.type = 'text/javascript';
                                        kdt.async = true;
                                        kdt.src = 'https://i.k-analytix.com/k.js';
                                        var s = document.getElementsByTagName('body')[0];

                                        console.log(s);
                                        s.parentNode.insertBefore(kdt, s);
                                    })();

                                    var visitorID;
                                    (function () {
                                        var period = 300;
                                        var limit = 20 * 1e3;
                                        var nTry = 0;
                                        var intervalID = setInterval(function () {
                                            var clear = limit / period <= ++nTry;

                                            console.log("visitorID trssy");
                                            if (typeof (Konduto.getVisitorID) !== "undefined") {
                                                visitorID = window.Konduto.getVisitorID();
                                                clear = true;
                                            }
                                            console.log("visitorID clear");
                                            if (clear) {
                                                clearInterval(intervalID);
                                            }
                                        }, period);
                                    })(visitorID);


                                    var page_category = 'order-list-page';
                                    (function () {
                                        var period = 300;
                                        var limit = 20 * 1e3;
                                        var nTry = 0;
                                        var intervalID = setInterval(function () {
                                            var clear = limit / period <= ++nTry;
                                            if (typeof (Konduto.sendEvent) !== "undefined") {

                                                Konduto.sendEvent(' page ', page_category); //Programmatic trigger event
                                                clear = true;
                                            }
                                            if (clear) {
                                                clearInterval(intervalID);
                                            }
                                        },
                                                period);
                                    })(page_category);
</script>
<?php } ?>

<script type="text/javascript">

    $(document).ready(function () {
        var $container = $('.order-details');
        $container.infinitescroll({
            animate: true,
            navSelector: '.pagination', // selector for the paged navigation 
            nextSelector: '.pagination a', // selector for the NEXT link (to page 2)
            itemSelector: '.order-details',
            loading: {
                finishedMsg: 'No more orders to load.',
                msgText: 'Loading...',
                img: 'image/theme/ajax-loader_63x63-0113e8bf228e924b22801d18632db02b.gif'

            },
            errorCallback: function () {
                $('.load-more-text').html('<?= $text_load_more?>');
                $('.load-more-loader').hide();
            }
        }, function (json, opts) {
            $('.load-more-text').html('<?= $text_load_more?>');
            $('.load-more-loader').hide();
        });

        $(window).unbind('.infscr');

        $(document).on('click', '.load_more', function () {
            var text = $('.load-more-text').html();
            $('.load-more-text').html('');
            $('.load-more-loader').show();
            $container.infinitescroll('retrieve');
            return false;
        });

        /**/
    });

    $(document).delegate('#approve_order', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrder',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Approved'
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
            }
        });
    });

    $(document).delegate('#approve_order_head_chef', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var parent_div = $(this).parent("div");
        var role = 'head_chef';
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Approved',
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
                setTimeout(function () {
                    window.location.reload(false);
                }, 100);
            }
        });
    });

    $(document).delegate('#reject_order_head_chef', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var order_status = 'Rejected';
        var customer_id = $(this).attr('data-custid');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var role = 'head_chef';
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));

        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: order_status,
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #FF5C23;display: block;line-height: 2; text-align:center;">Rejected</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
                setTimeout(function () {
                    window.location.reload(false);
                }, 100);
            }
        });
    });

    $(document).delegate('#approve_order_procurement', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var role = 'procurement';
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Approved',
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #8E45FF;display: block;line-height: 2; text-align:center;">Approved</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
                setTimeout(function () {
                    window.location.reload(false);
                }, 100);
            }
        });
    });

    $(document).delegate('#reject_order_procurement', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var customer_id = $(this).attr('data-custid');
        var order_status = $(this).attr('id');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);
        var role = 'procurement';
        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));
        console.log('Hi');
        console.log($('.col-md-3').children('.my-order-delivery').find('h3').text());

        console.log('Under progress');
        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrderByChefProcurement',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: 'Rejected',
                role: role
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #FF5C23;display: block;line-height: 2; text-align:center;">Rejected</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
                setTimeout(function () {
                    window.location.reload(false);
                }, 100);
            }
        });
    });

    $(document).delegate('#reject_order', 'click', function (e) {
        e.preventDefault();
        var order_id = $(this).attr('data-id');
        var order_status = 'Rejected';
        var customer_id = $(this).attr('data-custid');
        console.log(order_id + ' ' + customer_id + ' ' + order_status);

        var parent_div = $(this).parent("div");
        console.log(parent_div.attr("id"));

        $.ajax({
            url: 'index.php?path=account/order/ApproveOrRejectSubUserOrder',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id'),
                customer_id: $(this).attr('data-custid'),
                order_status: order_status
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                var approved = $('<li class="list-group-item"><div class="row"><div class="col-md-4"></div><div class="col-md-4"><div class="my-order-showaddress"><h3 class="my-order-title label" style="background-color: #FF5C23;display: block;line-height: 2; text-align:center;">Rejected</h3></div></div><div class="col-md-4"></div></div>');
                parent_div.html(approved);
                $('#orderstatus' + order_id).text(json.success);
                $('#editorder' + order_id).remove();
                setTimeout(function () {
                    window.location.reload(false);
                }, 100);
            }
        });
    });


  function cancelOrder(order_id) {
          
        if (!window.confirm("Are you sure?")) {
            return false;
        }

           $.ajax({
            url: 'index.php?path=account/order/refundCancelOrder',
            type: 'post',
            data: {
                order_id: order_id
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                   alert("Order ID #"+order_id+" is successfully cancelled");

                  ////if (json['status']) {
                  // alert("Order ID #"+order_id+" is successfully cancelled");


                 // } else {
                  //    alert("Order ID #"+order_id+" cancelling failed");
                 //}

                setTimeout(function () {
                    window.location.reload(false);
                }, 1000);
            }
        });
    }

    $(document).delegate('#cancelOrder', 'click', function (e) {

        e.preventDefault();

        if (!window.confirm("Are you sure?")) {
            return false;
        }
        console.log("cancelOrder click");
        console.log($(this).attr('data-id'));
        $('#cancelOrder').html('Wait...');
        var orderId = $(this).attr('data-id');
        $.ajax({
            url: 'index.php?path=account/order/refundCancelOrder',
            type: 'post',
            data: {
                order_id: $(this).attr('data-id')
            },
            dataType: 'json',
            success: function (json) {
                console.log(json);
                // if (json['status']) {
                //     alert("Order ID #"+orderId+" is successfully cancelled");


                // } else {
                //     alert("Order ID #"+orderId+" cancelling failed");
                // }

                setTimeout(function () {
                    window.location.reload(false);
                }, 1000);
            }
        });
    });

  

       $(document).delegate('.downloadaaaaaaaaaaaa', 'click', function (e) {
    var baseurl = window.location.origin + window.location.pathname;
    // alert(baseurl);
    var choice = confirm($(this).attr('data-confirm'));
    var added = "false";

    if (choice) {
      e.preventDefault();
      $orderid = $(this).attr('value');
    
      $store_id = $(this).attr('data-store-id');

      $.ajax({
        url: 'index.php?path=account/dashboard/getAvailableOrderProducts',
        dataType: 'json',
        type: 'POST',
        data: { 'order_id': $orderid },
        success: function (json) {
          $(json).each(function (index, item) {

            // each iteration
            var product_id = item.product_id;
            var quantity = item.quantity;
             if (quantity > 0) {
              added = "true";
               cart.add(product_id, quantity, 0, $store_id, '', '');

              console.log("added to cart");
            }
          });
            $iSec=2000;
            $iSec=($iSec*(json.length));
            //alert($iSec);
           // Show the div in 5s
          //  $("#workingMsgHiddenDiv").fadeIn(5000);

                var $div2 = $("#workingMsgHiddenDiv");
            if ($div2.is(":visible")) { return; }
            $div2.show();
            setTimeout(function() {
                $div2.hide();
            }, $iSec);


        },
        complete: function () {

          //baseurl = baseurl + "?path=checkout/checkoutitems";
          //var win = window.open(baseurl, '_blank');
         // if (win) {
            //Browser has allowed it to be opened
           // win.focus();
         // } else {
            //Browser has blocked it
            //alert('Please allow popups for this website');
          //}
          //opening new window, showing few items, as the products are adding slowly
         // alert('Available products from the selected order added to cart!');
        },
      });
    }
  });




function addOrderToCart(orderid,store_id) {

        
        if(!window.confirm("All the available products in this order ,will be added to cart.Are you sure?")) {
            return false;
        }
        console.log("additemstocart click");
        console.log(orderid);
           
        $.ajax({
            url: 'index.php?path=account/wishlist/addAvailableOrderProducts',
            type: 'post',
            data: {
                order_id: orderid
            },
            dataType: 'json',
            success: function(json) {
                console.log(json);
                
                setTimeout(function(){ window.location.reload(false); }, 1000);
                 var baseurl = window.location.origin + window.location.pathname;
                   baseurl = baseurl + "?path=checkout/checkoutitems";
           var win = window.open(baseurl, '_blank');
           if (win) {
            //Browser has allowed it to be opened
             win.focus();
           } else {
            //Browser has blocked it
            alert('Please allow popups for this website');
          }
          //opening new window, showing few items, as the products are adding slowly
         // alert('Available products from the selected order added to cart!');
            }
        });
    }



    $(document).delegate('#additemstocart', 'click', function(e) {

        e.preventDefault();
        
        if(!window.confirm("All the available products in this order ,will be added to cart.Are you sure?")) {
            return false;
        }
        console.log("additemstocart click");
        console.log($(this).attr('value'));
       // $('#addWishlisttocart').html('Wait...');        
        $.ajax({
            url: 'index.php?path=account/wishlist/addAvailableOrderProducts',
            type: 'post',
            data: {
                order_id: $(this).attr('value')
            },
            dataType: 'json',
            success: function(json) {
                console.log(json);
                
                setTimeout(function(){ window.location.reload(false); }, 1000);
                 var baseurl = window.location.origin + window.location.pathname;
                   baseurl = baseurl + "?path=checkout/checkoutitems";
           var win = window.open(baseurl, '_blank');
           if (win) {
            //Browser has allowed it to be opened
             win.focus();
           } else {
            //Browser has blocked it
            alert('Please allow popups for this website');
          }
          //opening new window, showing few items, as the products are adding slowly
         // alert('Available products from the selected order added to cart!');
            }
        });
    });


    setInterval(function () {
        location = location;
    }, 6000 * 1000); // 60 * 1000 milsec

 
//$('select').on("change",function(){
$(document).on('change', 'select.newddl', function(){
     
    var selectedvalue = this.value;
     //alert(this.value);
 
     var orderid =$("#action_id_"+selectedvalue+" option:selected").attr('order-id');     
     var type =$("#action_id_"+selectedvalue+" option:selected").attr('type');
    //alert(type);

    if(type=="Download cart")
  {
     var company = $("#action_id_"+selectedvalue+" option:selected").attr('order_company');
     excel(orderid,company);return;
  }
  else if(type=="Edit order")
  {
     var edit_url = $("#action_id_"+selectedvalue+" option:selected").attr('order_href');
location=edit_url;
return;
  }
   
  else if(type=="Report issue")
  {
       $('#reportissueModal').modal({
        show: 'true'
       
    });
    sendSelectedOrderID(orderid);
      
  }
   else if(type=="View items")
  {
        $('#viewProductsModal').modal({
        show: 'true'
    });
      viewProductsModal(orderid);
     
  }

    else if(type=="View real items")
  {
     var view_url = $("#action_id_"+selectedvalue+" option:selected").attr('view_href');

       location=view_url;
return;
  }
      

  else if(type=="Cancel order")
  {
      cancelOrder(orderid);
  }

   else if(type=="Add to cart")
  {
     var store_id = $("#action_id_"+selectedvalue+" option:selected").attr('data-store-id');

      addOrderToCart(orderid,store_id);
  }
   else if(type=="Accept delivery")
  {
     var accept_reject_href = $("#action_id_"+selectedvalue+" option:selected").attr('accept_reject_href');

       location=accept_reject_href;
return;
  }
  else if(type=="Report missed products")
  {
    $('#viewMissedProductsModal').modal({
        show: 'true'
    });
    viewMissedProductsModal(orderid);
  }
  else if(type=="Report rejected products")
  {
    $('#viewRejectedProductsModal').modal({
        show: 'true'
    });
    viewRejectedProductsModal(orderid);
  }
$('.newddl').prop('selectedIndex',0);  
});
 

    function excel(order_id, order_company) {
        //alert(order_company);
        url = 'index.php?path=account/order/export_products_excel&order_id=' + order_id + '&company=' + order_company;
        location = url;
    }
   function viewProductsModal($order_id) {
 
                //$('#edit-address-message').html('');
                //$('#edit-address-success-message').html('');
                console.log($order_id);
                console.log("order_id");
                $.ajax({
                    url: 'index.php?path=account/order/infoPopup',
                    type: 'get',
                    async: false,
                    data: {order_id: $order_id},
                    dataType: 'html',
                    cache: false,
                    success: function(json) {

                        console.log(json);
                        
                        //$('.order-details-form-panel').html(json['html']); 
                        $('.order-details-form-panel').html(json);

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                         alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        
                        return false;
                    }
                });
            }
   
   function viewMissedProductsModal($order_id) {
    console.log($order_id);
    console.log("order_id");
                $.ajax({
                    url: 'index.php?path=account/order/missingproducts',
                    type: 'get',
                    async: false,
                    data: {order_id: $order_id},
                    dataType: 'html',
                    cache: false,
                    success: function(json) {
                        console.log(json);
                        $('.missingproducts-details-form-panel').html(json);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        return false;
                    }
                });    
   }
   
   function viewRejectedProductsModal($order_id) {
    console.log($order_id);
    console.log("order_id");
                $.ajax({
                    url: 'index.php?path=account/order/rejectedproducts',
                    type: 'get',
                    async: false,
                    data: {order_id: $order_id},
                    dataType: 'html',
                    cache: false,
                    success: function(json) {
                        console.log(json);
                        $('.rejectedproducts-details-form-panel').html(json);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        return false;
                    }
                });    
   }
     



 function sendSelectedOrderID($order_id) {
    console.log($order_id);
                
                  var text ="<span class='col-sm-12 control-label orderlabel super' style='background: #FFE4CB;text-align: center;padding-top: 0px'>Order Id:"+$order_id+" </span><input hidden id='selectedorderid' name='selectedorderid' value="+$order_id+"></input>";
            $("#modal_bodyvalue").html(text);
             $("#reportissue-success-message").html('');
              $("#reportissue-message").html('');
               $("#input-issuesummary").val();

                 
            }

    
</script>
</body>

</html>

<style>

.editAddressModal modal-dialog {       
   
}
 .my-order-group
        {
 background: #F6F5F5;
box-shadow: 0px 6px 12px rgba(154, 154, 154, 0.25);
 
        }

        .my-order-list-headnew
        {
background: rgba(206, 196, 255, 0.43); 
        }

        .my-order .list-group-item:first-child {
     
    padding-left: 18px;
}
        .list-group-item {
        padding: 1px 2px;
        }
.newddl{
    font-size: 13px;line-height: 20px;   
}

.my-order-details { 
    font-size:14px
}
.btn-newreject {
    border: 0.3px solid #000000;
    box-sizing: border-box;
    filter: drop-shadow(0px 6px 30px rgba(0, 0, 0, 0.18));
    border-radius: 8px;
    background: none;
    color: #3F3D3D !important;
    font-family: Poppins;
    font-style: normal;
    font-weight: 400;
    font-size: 11px;
    line-height: 15px;
    margin: 0px 16px;
    padding: 8px 8px;
}
.btn-newapprove {
    background: green;
    border: 0.3px solid #0C9D46;
    box-sizing: border-box;
    box-shadow: 0px 6px 30px rgb(90 244 170 / 25%);
    border-radius: 8px;
    color: white !important;
    font-family: Poppins;
    font-style: normal;
    font-weight: 400;
    font-size: 11px;
    line-height: 15px;
    margin: 0px 16px;
    padding: 8px 8px;
}
 </style>