<?php echo $header; ?>
    <div class="checkout-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                <form role="form" id="place-order-form" novalidate="novalidate" class="bv-form" method="post">
                    <div id="accordion" class="checkout">
                        <div class="panel checkout-step">
                            <input type="hidden" value="<?php echo $loggedin; ?>" name="user_loggedin" id="user_loggedin">
                            <input type="hidden" value="" name="shipping_method" id="shipping_method">
                            <?php if($loggedin) { ?>
                                
                                <div>

                                    <span id="step-1" class="checkout-step-number checkout-step-color hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                                    <h4 class="checkout-step-title"> 

                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" > </a> <?= $loginform ?> 

                                    </h4>

                                    <a id='checkoutLogout' type='button' class='checkoutLogoutButton btn btn-primary checkoutChange'> Logout </a>
                                </div>  
                                <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" id="address-next"></a>
                            <?php } else { ?>

                            
                                <div> <span id="step-1" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                                    <h4 class="checkout-step-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" > <?= $text_login ?></a></h4>
                                </div>
                                <div id="collapseOne" class="collapse in">
                                    <div class="checkout-step-body">
                                        <div class="mydivs">
                                            <div class="row">
                                                <a href="#" type="button" class="btn btn-default col-sm-2" type="button" data-toggle="modal" data-target="#phoneModal"><?= $text_sign_in ?></a>

                                                <p style="font-size: 23px;margin-left: 15px;" class="col-sm-2"> <?= $text_or?></p>

                                                <a href="#" type="button" class="btn btn-default col-sm-4" type="button" data-toggle="modal" data-target="#signupModal-popup"><?= $text_register ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="panel checkout-step">
                            <div role="tab" id="headingTwo"> 

                                <span id="step-2" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                                <h4 class="checkout-step-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" id="address_panel_link">
                                        <?= $text_delivery_address ?>
                                    </a>

                                </h4>

                                <?php if($loggedin) { ?>
                                    <a type="button" class="btn btn-primary collapsed checkoutChangeButton checkoutChange"  data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="display: none;max-height: 32px;"> Change </a> 
                                <?php } ?>


                                <br/>
                                <h5 id="select-address" style="margin-left: 61px;color:#555">
                                   Please select address
                               </h5>
                                
                            </div>
                            <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseDeliveryOptions" id="delivery-option"></a>

                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="checkout-step-body">
                                    <div class="checout-address-step">
                                        <input type="hidden" value="<?php echo $city_id; ?>" name="shipping_city_id" id="shipping_city_id">
                                        <input type="hidden" value="" name="shipping_address_id" id="shipping_address_id">

                                        <div class="row" id="address-panel">
                                            <?php if($addresses){ ?>
                                             
                                                <?php foreach($addresses as $address){ ?>
                                                    <div class="col-md-6">
                                                        <div class="address-block">
                                                            <h3 class="address-locations">
                                                            <?php if($address['address_type'] == 'Home') { ?>
                                                                <?= $text_home_address ?>

                                                            <?php } elseif($address['address_type'] == 'Office') { ?>
                                                                    <?= $text_office ?>
                                                            <?php } else {?>
                                                                    <?= $text_other ?>
                                                            <?php }?>
                                                            </h3>
                                                            <h4 class="address-name"><?= $address['name'] ?></h4>
                                                            <p><?php echo $address['flat_number'].', ' ?><br>
                                                            <?php echo $address['building_name'] ?>
                                                            <br><?php echo $address['city']; ?>
                                                            </p>


                                                            <?php if($this->config->get('config_store_location') == 'zipcode') { ?>

                                                                <?php $enableAddress = true; foreach ($store_data as $os): ?>

                                                                    <?php if( !in_array($address['zipcode'], $os['servicable_zipcodes']) ) { 
                                                                        $enableAddress = false;
                                                                    } ?>

                                                                    
                                                                <?php endforeach ?> 

                                                                <?php if( $enableAddress ) { ?>
                                                                    <a  data-address-id="<?= $address['address_id'] ?>" id="open-address" class="btn btn-primary btn-block"><?= $text_deliver_here ?></a>
                                                                <?php } else { ?>
                                                                    <a href="#" class="btn btn-grey btn-block disabled" role="button"><?= $text_not_deliver_here ?></a>
                                                                <?php } ?>
                                                            

                                                            <?php } else { ?>

                                                                <?php if( $address['show_enabled'] ) { ?>
                                                                    <a  data-address-id="<?= $address['address_id'] ?>" id="open-address" class="btn btn-primary btn-block"><?= $text_deliver_here ?></a>
                                                                <?php } else { ?>
                                                                    <a href="#" class="btn btn-grey btn-block disabled" role="button"><?= $text_not_deliver_here ?></a>
                                                                <?php } ?>
                                                            

                                                            <?php } ?>
                                                            
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-12"><a href="#" type="button" class="btn-link" data-toggle="modal" data-target="#addressModal"><i class="fa fa-plus-circle"></i> <?= $text_new_delivery_adddress?></a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel checkout-step">
                            <div role="tab" id="headingDeliveryOptions"> 
                                <span id="step-3" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                                <h4 class="checkout-step-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseDeliveryOptions" id="delivery_option__panel_link" > <?= $text_delivery_option?> </a> </h4>

                                <?php if($loggedin) { ?>
                                    <a type="button" class="btn btn-primary collapsed checkoutDeliveryOptionsChangeButton checkoutChange"  data-toggle="collapse" data-parent="#accordion" href="#collapseDeliveryOptions" style="display: none;max-height: 32px;"> Change </a> 

                                    <!-- <a type="button" class="btn btn-primary collapsed checkoutChangeTimeButton checkoutChange"  data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="display: none;max-height: 32px;"> Change </a>  -->   

                                <?php } ?>     

                                    <br/>
                                   <h5 id="select-delivery-method" style="margin-left: 61px;color:#555">
                                       Please select delivery method
                                   </h5>
                                
                            </div>
                            <div id="collapseDeliveryOptions" class="panel-collapse collapse">
                                <div class="checkout-step-body">
                                    <input type="hidden" value="" name="dates_selected" >
                                    <?php foreach ($store_data as $os): ?>

                                            <b>     <?php echo $os['name'] ?> </b>
                                            <div class="checkout-payment-mode" id="shipping-method-wrapper-<?php echo $os['store_id'] ?>">
                                                <!-- shipping method will goes here -->

                                            </div>
                                    <?php endforeach ?> 
                                    <div class="goto-next">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <a class="collapsed btn btn-default" role="button" data-toggle="collapse" data-parent="#accordion" href="#" id="timeslot-next" > <?= $text_next?> </a>

                                                <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" id="timeslot-next-hidden"></a>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel checkout-step">
                            <div role="tab" id="headingThree"> <span id="step-4" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                                <h4 class="checkout-step-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" id="delivery_time_panel_link" > <?= $text_delivery_timedate?> </a> </h4>

                                <a type="button" class="btn btn-primary collapsed checkoutChangeTimeButton checkoutChange"  data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="display: none;max-height: 32px;"> Change </a>                                  
    
                                   <br/>
                                    <h5 id="select-timeslot" style="margin-left: 61px;color:#555">
                                        Please select a timeslot
                                   </h5>


                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="checkout-step-body">
                                    <input type="hidden" value="" name="shipping_time_selected" >

                                    <?php foreach ($store_data as $os): ?>

                                        <b>     <?php echo $os['name'] ?> </b>
                                        <div class="checkout-time-table" id="delivery-time-wrapper-<?php echo $os['store_id'] ?>">

                                        </div>              
                                    <?php endforeach ?> 
                                    <a class="collapsed btn btn-grey" disabled="disabled" role="button" data-toggle="collapse" data-parent="#accordion" href="#" id="payment-next">  <?= $text_next?>  

                                    </a>

                                    <!-- <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" id="delivery-time-wrapper"></a> -->

                                    <?php if(!$checkout_question_enabled) { ?>
                                        <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" id="delivery-time-wrapper"></a>
                                    <?php } else { ?>
                                        <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseQuestion" id="delivery-time-wrapper"></a>
                                    <?php } ?>
                                    

                                </div>
                            </div>
                        </div>

                        <?php if($checkout_question_enabled){ ?>
                            <div class="panel checkout-step">
                                <div role="tab" id="headingFour"> <span id="step-5" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                                    <h4 class="checkout-step-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseQuestion" id="question_panel_link"  > <?= $text_question?> </a> </h4>
                                </div>
                                <div id="collapseQuestion" class="panel-collapse collapse">
                                    <div class="checkout-step-body">
                                        
                                        <div style="margin-bottom: 30px;">

                                           

                                                <?php foreach ($questions as $question): ?>

                                                    <div style="margin-top: 20px" class="form-group">

                                                        <b><?php echo $question['question'] ?> </b>

                                                        <div style="margin-top: 5px">
                                                            <label class="control control--radio radio-inline" style="display: inline;">
                                                            
                                                                <input type="radio" name="question-<?php echo $question['checkout_question_id'] ?>"   data-id="<?php echo $question['checkout_question_id'] ?>" value="yes" class="question-inputs" />

                                                                <?= $text_yes?>
                                                                <div class="control__indicator"></div>

                                                            </label>

                                                            <label class="control control--radio radio-inline" style="display: inline;">
                                                                
                                                                
                                                                <input type="radio" name="question-<?php echo $question['checkout_question_id'] ?>"   data-id="<?php echo $question['checkout_question_id'] ?>"  value="no" class="question-inputs"/>

                                                                <?= $text_no?>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?> 

                                            <!-- <form id="question-forms" action="">
                                                <input type="radio" name="question" value="yes" class="question-inputs" />
                                            </form> -->
                                        </div>

                                        <a class="collapsed btn btn-grey" disabled="disabled" role="button" data-toggle="collapse" data-parent="#accordion" href="#" id="question-payment-next">  <?= $text_next?>  
                                        </a>

                                        <a type="hidden" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" id="question-next-button"></a>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                       


                        <div class="panel checkout-step">
                            <?php if($checkout_question_enabled) { ?>
                                <div role="tab" id="headingFour"> <span id="step-6" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <?php } else { ?>
                                <div role="tab" id="headingFour"> <span id="step-5" class="checkout-step-number hidden-xs"><i class="fa fa-check" aria-hidden="true"></i></span>
                            <?php } ?>

                            
                                <h4 class="checkout-step-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" id="payment_panel_link"  > <?= $text_payment?> </a> </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="checkout-step-body">
                                    <div id="payment-method-wrapper" class="checkout-payment-mode">
                                        <!-- payment method will goes here -->
                                    </div>
                                    <div id="pay-confirm-order">

                                    </div>

                                    <div id="payment-method-wrapper-loader">
                                        <center><div class="payment-loader"></div></center>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkout-sidebar" id="checkout-total-wrapper">

                                

                            </div>
                            <div class="login-loader" style="display: none;"></div>

                                
                           

                            <div class="promo-code-success-message" style="color: green;">
                            </div>
                            <div class="promo-code-message" style="color: red;">
                            </div>

                            <?php if($this->config->get('coupon_status')) { ?>

                                <div class="checkout-promocode-form">
                                    <form class="promo-form">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label class="control-label sr-only" for="code"></label>
                                                <input type="text" name="coupon" class="form-control" placeholder="<?= $text_promo_code?>" id="coupon" required="">
                                                <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="promo-form-button"><?= $button_apply?></button>
                                          </span>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                    </form>
                                </div>
                            <?php } ?>
                             
                            <form id="comment-order-form">

                            <?php $this->load->model('account/address'); ?>
                            <?php foreach ($arrs as $key=> $products) { ?>

                                <div class="checkout-sidebar-merchant-box-old">
                                    <div class="checkout-cart-merchant-box-old"> <span class="checkout-cart-merchant-name"><?php echo $this->model_account_address->getStoreNameById($key); ?></span></div>
                                    
                                    <div class="checkout-promocode-form">
                                        
                                        <textarea name="dropoff_notes-<?= $key?>" class="form-control" maxlength="200" placeholder="<?= $text_dropoff_notes?>" id="dropoff_notes" style="height: 100px;"></textarea>
                                        
                                    </div>

                                </div>
                            <?php } ?>
                            </form>

                            <!-- Start reward form --> 
                            <?php if($this->config->get('reward_status')) { ?>
                                <div class="checkout-promocode-form">
                                
                                        
                                    <h5 id="reward-error" style="display: none;color: red;"></h5>                                        
                                    <h5 id="reward-success" style="display: none;color: green;"></h5>
                                        
                                    <form id="coupon-form" class="coupon-forms" action="">
                                        <div class="form-group">

                                            <div class="input-group">
                                                <input type="text" name="reward" class="form-control" placeholder="<?= $entry_reward_points ?>" maxlength="10" />

                                                <span class="input-group-btn">
                                                    <button id="button-reward" class="btn btn-primary" type="button">
                                                    <?= $button_add ?>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>                                        
                                        
                                </div>
                            
                            <?php } ?>
                            
                                   
                            <!-- END reward form --> 

                            <?php $this->load->model('account/address'); ?>
                            <?php foreach ($arrs as $key=> $products) { ?>

                                <div class="checkout-sidebar-merchant-box">
                                    <div class="checkout-cart-merchant-box"> <span class="checkout-cart-merchant-name"><?php echo $this->model_account_address->getStoreNameById($key); ?></span> <span class="checkout-cart-merchant-item"><?php echo $this->cart->getTotalProductsByStore($key); ?></span> </div>
                                    <div class="checkout-cart-products">
                                        <div class="collapse" id="collapseExample<?= $key ?>">
                                            <div class="checkout-item-list">
                                                <?php $i = 1; foreach ($products as $product) { ?>

                                                    <div class="checkout-cart-item">
                                                        <div class="checkout-item-count"><?= $product['quantity']?></div>
                                                        <div class="checkout-item-img"><img src="<?= $product['thumb'] ?>" alt="" class="img-responsive"></div>
                                                        <div class="checkout-item-name-box">
                                                            <div class="checkout-item-title"><?= $product['name'] ?></div>
                                                            <div class="checkout-item-unit"><?= $product['unit'] ?>
                                                                <!-- &nbsp;
                                                                <?php if($product['product_type'] == 'replacable') { ?>
                                                                <span class="badge badge-success replacable" style="cursor: pointer;" data-key='<?= $product["key"] ?>' data-value="replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_replacable_title ?>">
                                                                 <?= $text_replacable ?>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span  class="badge badge-danger replacable"  style="cursor: pointer;" data-key='<?= $product["key"] ?>' data-value="not-replacable" data-toggle="tooltip" data-placement="left" title="<?= $text_not_replacable_title ?>">
                                                                    <?= $text_not_replacable ?>
                                                                </span>
                                                            <?php } ?> -->
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="checkout-item-price"><?php echo $product['total']; ?></div>
                                                    </div>

                                                <?php $i++; } ?>
                                            </div>
                                        </div>
                                        <div class="checkout-viewmore-btn"> <a class="btn-link" role="button" data-toggle="collapse" href="#collapseExample<?= $key ?>" aria-expanded="false" aria-controls="collapseExample<?= $key ?>" ><?= $text_view ?> <?php echo $this->cart->getTotalProductsByStore($key); ?> <?= $text_item ?></a> </div>
                                    </div>
                                </div>
                            <?php $i++; } ?>



                            <!-- Continue shopping --> 
                                <div class="checkout-promocode-form">
                                
                                    <div class="form-group">
                                        <span class="input-group-btn">
                                            <a id="button-reward" href="<?php echo $continue; ?>" class="btn btn-primary" style="width: 100%;height: 100%;" type="button"><?php echo $button_continue; ?>
                                            </a>
                                        </span>
                                    </div>
                                
                                </div>
                            <!-- END Continue shopping --> 

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= $login_modal ?>
    <?= $signup_modal ?>
    <?= $forget_modal ?>

    <div class="addressModal">
        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">
                            <div class="col-md-12">
                                <h2><?= $text_new_delivery_adddress ?></h2>
                            </div>
                            <div id="address-message" class="col-md-12" style="color: red">
                            </div>
                            <div id="address-success-message" style="color: green">
                            </div>
                            <div class="addnews-address-form">
                                    <!-- Multiple Radios (inline) -->
                                <form id="new-address-form">
                                        
                                
                                    <input type="hidden" value="<?php echo $city_id; ?>" name="shipping_city_id" id="shipping_city_id">
                                    <input type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode" id="shipping_zipcode">

                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="address"></label>
                                        <div class="col-md-12">
                                            <div class="select-locations">
                                                <label class="control control--radio"><?= $text_home_address?>
                                                    <input type="radio" name="modal_address_type" value="home" checked="checked" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio"><?= $text_office?>
                                                    <input type="radio" value="office" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio"><?= $text_other?>
                                                    <input type="radio" value="other" name="modal_address_type" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="name"><?= $text_name ?></label>
                                            <input id="name" name="modal_address_name" type="text" placeholder="Name" value="<?= $name ?>" class="form-control input-md" required="">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for="flat"><?= $text_flat_house_office?></label>
                                        <div class="col-md-12">
                                            <input id="flat" name="modal_address_flat" type="text" placeholder="45, Sunshine Apartments" class="form-control input-md" required="">
                                        </div>
                                    </div>
                                  

                                    <input id="street" name="modal_address_street" type="hidden"  class="form-control input-md">
                                        
                                    <input id="picker_city_name" name="picker_city_name" type="hidden" value="">
                                    
                                    <!-- Text input-->

                                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>
                                            
                                            <?php if($check_address) { ?>

                                                <div class="col-md-12">
                                                    <div class="input-group">

                                                        <input id="Locality"  name="modal_address_locality" type="text"  placeholder="<?= $text_flat_house_office?>" class="form-control input-md LocalityId" required="">                                                    
                                                        <span class="input-group-btn">

                                                            <button id="locateme" class="btn btn-default disabled" style="color: #333;background-color: #fff;border-color: #ccc;line-height: 2.438571; " type="button" data-toggle="modal" onclick="openGMap()" data-target="#GMapPopup"  ><i class="fa-crosshairs fa"></i> <?= $locate_me ?> </button>

                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-12">
                                                    <input  name="modal_address_locality" id="Locality" type="text"  class="form-control input-md LocalityId" required="">
                                                </div>
                                            <?php } ?>

                                        </div>

                                    <?php } else { ?>
                                        
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="Locality"><?= $text_locality?></label>
                                            
                                            <div class="col-md-12">

                                                <?php if( defined('const_latitude') && defined('const_longitude') && !empty(const_latitude) && !empty(const_longitude) ) { ?>
                                                    

                                                    <input  name="modal_address_locality" id="Locality" type="text" class="form-control input-md LocalityId" required="">
                                                    
                                                <?php } else { ?>
                                                    
                                                    <input  name="modal_address_locality" id="Locality" type="text" style="background-color:#DEDEDE;" readonly value="<?= $address_locality ?>" class="form-control input-md" required="">

                                                

                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    

                                    <?php if($this->config->get('config_store_location') == 'zipcode') { ?>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" for="zipcode"><?= $label_zipcode ?></label>
                                            <div class="col-md-12">
                                                <input id="shipping_zipcode" type="text" value="<?php echo $zipcode; ?>" name="shipping_zipcode" class="form-control input-md" disabled="true">
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <input id="shipping_zipcode" type="hidden" value="<?php echo $zipcode; ?>" name="shipping_zipcode">
                                    <?php } ?>
                                    

                                    
                                    <!-- Button -->
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button id="singlebutton" name="singlebutton" type="button" class="btn btn-primary" onclick="saveInAddressBook()"><?= $text_save?></button>
                                            <button type="button" class="btn btn-grey" data-dismiss="modal"><?= $text_close?></button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitude" value="<?= $latitude ?>" />
                                    <input type="hidden" name="longitude" value="<?= $longitude ?>" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="GMapPopup">
        <div class="modal fade" id="GMapPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="row">

                            <div class="col-md-12">
                                <center> 
                                    <h2><?= $text_your_location ?> </h2>
                                </center>
                            </div>
                        </div>

                        <div id="wrapper">
                           
                            <div id="us1" style="width: 100%; height: 400px;"></div> 
                           
                           <div id="over_map">

                                <div class="input-group">

                                    <input  name="modal_address_locality" type="text" id="gmap-input" class="form-control input-md LocalityId LocalityId2" required="" >                                                    
                                    <span class="input-group-btn">

                                        <button class="btn btn-default" id="detect_location" style="color: #333;background-color: #fff;border-color: #ccc;width: 150px;line-height: 2.438571; " type="button"  onclick="getLocation()"><i class="fa fa-location-arrow"></i> <?= $detect_location ?></button>

                                    </span>
                                </div>
                                
                           </div>
                        </div>
                        
                        <style>
                           #wrapper { position: relative; }
                           #over_map { position: absolute; top: 10px; padding-right: 12px;
                                        padding-left: 12px;  z-index: 99; width: 100%}
                        </style>

                        <script type="text/javascript">
                            

                            
                        </script>
                        <div class="row" style="margin-top: 10px;">
                            
                            <center>
                                <button id="saveLatLng" type="button" class="btn btn btn-primary" onclick="saveLatLng()"><?= $text_ok?></button>
                            </center>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        .pac-container {
          z-index: 99999999;
        }
        #map * {
            overflow:visible;
        }
    </style>

    <?= $footer ?>
   
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/side-menu-script.js"></script>

    

    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/header-sticky.js"></script>
    
    <!-- <script src="<?= $base;?>front/ui/theme/mvgv2/js/jquery.maskedinput.js" type="text/javascript"></script> -->
    
    <!-- <link rel="stylesheet" href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap-iso.css" /> -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!-- <script type="text/javascript" src="<?= $base;?>front/ui/theme/mvgv2/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="<?= $base;?>front/ui/theme/mvgv2/css/bootstrap-datepicker3.css"/> -->
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?= $this->config->get('config_google_api_key') ?>&libraries=places"></script>
    <script type="text/javascript" src="<?= $base?>admin/ui/javascript/map-picker/js/locationpicker.jquery.js?v=2.3"></script>
    
    <script type="text/javascript">
        console.log("map address");
            
        $('#us1').locationpicker({
            location: {
                latitude: <?= $latitude?$latitude:0 ?>,
                longitude: <?= $longitude?$longitude:0 ?>
            },  
            radius: 0,
            inputBinding: {
                latitudeInput: $('input[name="latitude"]'),
                longitudeInput: $('input[name="longitude"]'),
                locationNameInput: $('.LocalityId')
            },
            enableAutocomplete: true,
            zoom:13,

        }); 


        function saveLatLng() {
            $('#GMapPopup').modal('hide');
            $('.LocalityId').val($('.LocalityId').val());
        }


        function openGMap() {

            $("#GMapPopup").on('shown.bs.modal', function () {
                $('#us1').locationpicker('autosize');
            });
        }

        function GMapPopupInput() {

            var acInputs = document.getElementsByClassName("LocalityId2");

            

            var autocomplete = new google.maps.places.Autocomplete(acInputs);
            
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    
                console.log("latitude");
                console.log(autocomplete);
                $('#us1').locationpicker({
                    location: {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    },  
                    radius: 0,
                    inputBinding: {
                        latitudeInput: $('input[name="latitude"]'),
                        longitudeInput: $('input[name="longitude"]'),
                        locationNameInput: $('.LocalityId2')
                    },
                    enableAutocomplete: true,
                    zoom:13,
                });
            });
        }

        

        function initialize() {

            var acInputs = document.getElementsByClassName("LocalityId");

            for (var i = 0; i < acInputs.length; i++) {

                var autocomplete = new google.maps.places.Autocomplete(acInputs[i]);
                
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                });
            }
        }

        function getLocation() {

            $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $text_locating ?>');
            console.log("getLocation");

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            //var latlon = position.coords.latitude + "," + position.coords.longitude;
            console.log("showPosition");
            console.log(position);

            

            $('#us1').locationpicker({
                location: {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                },  
                radius: 0,
                inputBinding: {
                    latitudeInput: $('input[name="latitude"]'),
                    longitudeInput: $('input[name="longitude"]'),
                    locationNameInput: $('.LocalityId')
                },
                enableAutocomplete: true,
                zoom:13,
            });

            console.log($('#us1').locationpicker('location'));

            $('#detect_location').html('<i class="fa fa-location-arrow"></i> <?= $detect_location ?>');
        }
    </script>


<?php if ($kondutoStatus) { ?>

    
<script src="https://i.k-analytix.com/konduto.min.js" type="text/javascript"></script>

<script type="text/javascript">

  var __kdt = __kdt || [];

  var public_key = '<?php echo $konduto_public_key ?>';

  console.log("public_key");
  console.log(public_key);
__kdt.push({"public_key": public_key}); // The public key identifies your store
__kdt.push({"post_on_load": false});   
  (function() {
           var kdt = document.createElement('script');
           kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
           kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
           var s = document.getElementsByTagName('body')[0];

           console.log(s);
           s.parentNode.insertBefore(kdt, s);
            })();

            var visitorID;
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
      var clear = limit/period <= ++nTry;

      console.log("visitorID trssy");
      if (typeof(Konduto.getVisitorID) !== "undefined") {
               visitorID = window.Konduto.getVisitorID();
               clear = true;
      }
      console.log("visitorID clear");
      if (clear) {
     clearInterval(intervalID);
    }
    }, period);
    })(visitorID);

    var page_category = 'checkout-page';
    (function() {
      var period = 300;
      var limit = 20 * 1e3;
      var nTry = 0;
      var intervalID = setInterval(function() {
               var clear = limit/period <= ++nTry;
               if (typeof(Konduto.sendEvent) !== "undefined") {

                Konduto.sendEvent (' page ', page_category); //Programmatic trigger event
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

    function show() {
        $(".overlayed").show();
    }
    function hide() {
        $(".overlayed").hide();
    }
    $(document).ready(function() {

        $('.replacable').on('click', function(){
            console.log("replacable");
            if($(this).attr('data-value') == 'replacable') {
                //toggle
                console.log("repl yes");
                $(this).attr('data-value', 'not-replacable');
                $(this).removeClass('badge-success');
                $(this).addClass('badge-danger');
                $(this).html('<?= $text_not_replacable ?>');
                
                $(this).attr('title', '<?= $text_not_replacable_title ?>');
            } else {
                console.log("nss");
                $(this).attr('data-value', 'replacable');
                $(this).removeClass('badge-danger');
                $(this).addClass('badge-success');
                $(this).html('<?= $text_replacable ?>');
                $(this).attr('title', '<?= $text_replacable_title ?>');
            }   

            $product_type = $(this).attr('data-value');  

            console.log($product_type);
            $this = $(this);
            
            
            if($this.attr('data-key').length > 0) {
                console.log("replacable first"+$product_type);
                console.log($this.attr('data-key'));
                var response = cart.update_product_type($this.attr('data-key'),$product_type);
            }
        });

        $('.dropdown-toggle').dropdown();
        
        var divs = $('.mydivs>div');
        var now = 0; // currently shown div
        divs.hide().first().show();
        $("button[name=next]").click(function(e) {
            divs.eq(now).hide();
            now = (now + 1 < divs.length) ? now + 1 : 0;
            divs.eq(now).show(); // show next
        });
        $("button[name=prev]").click(function(e) {
            divs.eq(now).hide();
            now = (now > 0) ? now - 1 : divs.length - 1;
            divs.eq(now).show(); // or .css('display','block');
            //console.log(divs.length, now);
        });
    });
    </script>


<script type="text/javascript">
    $('#button-reward').on('click', function() {
        $.ajax({
            url: 'index.php?path=checkout/reward/reward',
            type: 'post',
            data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
            dataType: 'json',
            beforeSend: function() {
                $('#button-reward').button('loading');
                $('#reward-error').hide();
                $('#reward-success').hide();
            },
            complete: function() {
                $('#button-reward').button('reset');
            },
            success: function(json) {
                if (json['error']) {
                    $('#reward-error').html('<p id="error">'+json['error']+'</p>').show();
                }else{
                    $('#reward-success').html('<p id="success">'+json['success']+'</p>').show();
                    loadTotals($('input#shipping_city_id').val());
                }
            }
        });
    });
    $(document).delegate('.addressmenu li', 'click', function() {
        $('input[name="shipping_contact_no"]').val($(this).attr('data-contact_no'));
        $('input[name="shipping_name"]').val($(this).attr('data-name'));
        $('textarea[name="shipping_address"]').val($(this).attr('data-address'));
        $('input[name="shipping_city_id"]').val($(this).attr('data-city_id'));

        $('input[name="flat_number"]').val($(this).attr('data-flat_number'));
        $('input[name="building_name"]').val($(this).attr('data-building_name'));
        $('input[name="landmark"]').val($(this).attr('data-landmark'));

        loadTotals($(this).attr('data-city_id'));
    });
</script>

<script type="text/javascript">

// Load totals
function loadTotals($city_id) {

    $('#checkout-total-wrapper').html('<center><div class="login-loader" style=""></div></center>');

    $.ajax({
        url: 'index.php?path=checkout/totals&city_id=' + $city_id,
        type: 'post',
        dataType: 'html',
        cache: false,
        async: true,
        beforeSend: function() {
            
        },
        success: function(html) {
            $('#checkout-total-wrapper').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
//Load Delivery Time
function loadDeliveryTime(store_id) {

    $('#delivery-time-wrapper-'+store_id+'').html('<center><div class="login-loader" style=""></div></center>');

    console.log("loadDeliveryTime");
    var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value')
    console.log(shipping_method);
    console.log("shipping_method");
    //$('input[id="shipping_method"]').val(shipping_method);

    if($('input[id="shipping_method"]').val() == 'express.express') {
        $('#timeslot-next-hidden').attr("href","#collapseFour");
        $('#delivery_time_panel_link').attr("href","");

        $('input[name="shipping_time_selected"]').val('');
        $('input[name="dates_selected"]').val('');
        saveOrder();
    } else {
        $('#timeslot-next-hidden').attr("href","#collapseThree");
        $('#delivery_time_panel_link').attr("href","#collapseThree");
    }

    $.ajax({
        url: 'index.php?path=checkout/delivery_time&shipping_method='+shipping_method+'&store_id='+store_id+'',
        type: 'get',
        dataType: 'html',
        cache: false,
        async: true,
        beforeSend: function() {
            // $('#delivery-time-wrapper-'+store_id+'').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            console.log(html);
            $('#delivery-time-wrapper-'+store_id+'').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}


function getTimeSlot(store_id,date) {

    var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value')
    
    //$('input[id="shipping_method"]').val(shipping_method);

    $.ajax({
        url: 'index.php?path=checkout/delivery_time/get_time_slot&shipping_method='+shipping_method+'&store_id='+store_id+'&date='+date+'',
        type: 'get',
        dataType: 'html',
        cache: false,
        async: false,
        beforeSend: function() {
        },
        success: function(html) {
            $('#timeslot_'+store_id+'').html(html);
            loadPaymentMethods();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Methods related with shipping
$(document).ready(function() {
    console.log("logged in as ");
    <?php

        if($loggedin && $profile_complete) { ?>
            
            console.log("remove href");
            $('#delivery_option__panel_link').attr("href","");
            $('#delivery_time_panel_link').attr("href","");
            $('#payment_panel_link').attr("href","");
            document.getElementById('address-next').click();
            
        <?php } else { ?>
            $('#address_panel_link').attr("href","");
            $('#delivery_time_panel_link').attr("href","");//#collapseThree
            $('#delivery_option__panel_link').attr("href","");//#collapseDeliveryOptions
            $('#payment_panel_link').attr("href","");//#collapseFour
        <?php }
        if ($shipping_required) { 

        foreach ($store_data as $os): 
        ?>
            console.log("call to loadShippingMethods");
            loadShippingMethods('<?php echo $os["store_id"] ?>'); 
            
        <?php
        endforeach;
        } ?>
    
    loadPaymentMethods();
});

<?php
if ($shipping_required) { 

?>
    // Load shipping methods
    function loadShippingMethods(store_id) {
        data = {
            store_id : store_id
        }
        
        $.ajax({
            url: 'index.php?path=checkout/shipping_method',
            type: 'post',
            dataType: 'html',
            data:data,
            cache: false,
            async: false,
            beforeSend: function() {
                $('#shipping-method-wrapper-'+store_id+'').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');

            },
            success: function(html) {
                console.log("shipping-method-wrapper");
                console.log(html);
                $('#shipping-method-wrapper-'+store_id+'').html(html);
                saveShippingMethod(store_id);   
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    // Save the selected shipping method
    function saveShippingMethod(store_id) {
        console.log("save-shipping-method");
        console.log(store_id);

        $('#timeslot-next').removeAttr('disabled');

        var shipping_method = $('input[name=\'shipping_method-'+store_id+'\']:checked').attr('value');
        console.log(shipping_method); 
        if (shipping_method == undefined) {
            shipping_method = 0;
        }

        $.ajax({
            url: 'index.php?path=checkout/shipping_method/save',
            type: 'post',
            data: {
                store_id:store_id,
                shipping_method: shipping_method
            },
            dataType: 'html',
            cache: false,
            async: true,
            success: function(json) {

                console.log("shipng resp"); 
                console.log(json); 

                var obj = JSON.parse(json);
                console.log(obj.shipping_name); 

                if (json['redirect']) {
                    //location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#shipping-method-wrapper').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else {

                    if(obj.shipping_name) {

                        if(shipping_method == 'express.express') {
                            $('#select-timeslot').html(obj.express_minutes);    
                            $('#select-delivery-method').html("Selected delivery method : "+obj.shipping_name);    
                        } else {
                            $('#select-timeslot').html('Please select a timeslot');    
                            $('#select-delivery-method').html("Selected delivery method : "+obj.shipping_name);        
                        }
                        
                    }
                    

                    loadDeliveryTime(store_id);
                    loadTotals($('input[name="shipping_city_id"]').val());
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

     <?php

} ?>




// Load payment methods
function loadPaymentMethods() {
    console.log("load loadPaymentMethods");

    $.ajax({
        url: 'index.php?path=checkout/payment_method',
        type: 'post',
        dataType: 'html',
        cache: false,
        async: false,
        beforeSend: function() {
            $('#payment-method-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            console.log("loaded loadPaymentMethods");
            $('#payment-method-wrapper').html(html);
            savePaymentMethod();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
// Save the selected payment method
function savePaymentMethod() {
    console.log("savepayment");

    var payment_method = $('input[name=\'payment_method\']:checked').attr('value');
    var payment_wallet_method = $('input[name=\'payment_wallet_method\']:checked').attr('value');
    if (payment_method == undefined) {
        payment_method = 0;
    }
    if (payment_wallet_method == undefined) {
        payment_wallet_method = 0;
    }
    console.log(payment_method);
    console.log(payment_wallet_method);
    $('#payment-method-wrapper-loader').show();
    $('#payment-method-wrapper').hide();
    $('#pay-confirm-order').hide();
    

    $.ajax({
        url: 'index.php?path=checkout/payment_method/save',
        type: 'post',
        data: {
            payment_method: payment_method,
            payment_wallet_method: payment_wallet_method
        },
        dataType: 'html',
        cache: false,
        async: true,
        beforeSend: function() {

            $('#new-confirm-order').attr('id', 'confirm-order');
            $('.confirm-order-loader').css({ 'display': "none" });
            $('#confirm-order').css({ 'display': "block" });
            $('#pay-confirm-order').css({ 'display': "none" });
            $('.confirm-order-text').html('Confirm button');
            // confirm-order,confirm-order-loader,pay-confirm-order
        },
        success: function(json) {
            console.log(json);
            if (json['redirect']) {
                //  location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#payment-method-wrapper').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            } else {
                loadConfirm();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        },
        complete: function() {

            $('#payment-method-wrapper-loader').hide();
            $('#payment-method-wrapper').show();
            $('#pay-confirm-order').show();
        },
    });
}
// Load confirm page
function loadConfirm() {
    console.log("loadConfirm");
    saveOrder();
}
function saveAddress() {
    return true;
}
function saveNewTimeSlot(store_id,timeslot,date) {
    console.log("saveTimeSlot new");
    
    $('#payment-next').removeAttr('disabled');
    $('#payment-next').removeClass('btn-grey');
    $('#payment-next').addClass('btn-default');

    data = {
        store_id :store_id,
        date : date,
        timeslot :timeslot
    }

    console.log(data);

    $.ajax({
        url: 'index.php?path=checkout/delivery_time/save',
        type: 'post',
        data:data,
        dataType: 'html',
        beforeSend: function() {
            //$('#confirm-wrapper').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            //$('#confirm-wrapper').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

    return false;
}

function saveOrder() {

    console.log("saveOrder");

    /*$.ajax({
        url: 'index.php?path=checkout/confirm/confirmPayment',
        type: 'post',
        data: $('#place-order-form').serialize(),
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#confirm-order').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            console.log(html);
            $('#confirm-order').html(html);

            <button type="button" id="button-confirm" class="btn-account-checkout btn-large btn-orange">CONFIRM ORDER</button>

            <button type="button" id="confirm-order" class="collapsed btn btn-default">
                <span class="confirm-order-text"><?= $button_confirm ?></span>
                <div class="confirm-order-loader" style="display: none;"></div>
            </button>
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

    return false;*/
    $error = false;

    var shipping_name = $('input[name="shipping_name"]').val();
    var shipping_contact_no = $('input[name="shipping_contact_no"]').val();
    var shipping_address = $('textarea[name="shipping_address"]').val();
    var shipping_city_id = $('input[name="shipping_city_id"]').val();

    var landmark = $('input[name="landmark"]').val();
    var building_name = $('input[name="building_name"]').val();
    var flat_number = $('input[name="flat_number"]').val();
    var address_type = $('input[name="address_type"]').val();


    var dropoff_notes = $('textarea[name="dropoff_notes"]').val();
    

    //$('input[name="shipping_address_id"]').val($(this).attr('data-address-id'));
    if ($('input[name="shipping_address_id"]').val().length <= 0) {
        $error = true;
        console.log($('input[name="shipping_address_id"]').val()+"err");
        $('input[name="shipping_address_id"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }

    appendDataToSend = '';
    <?php foreach ($store_data as $os):  ?>
        var shipping_method = $('input[name=\'shipping_method-'+<?php echo $os['store_id'] ?>+'\']:checked').attr('value')
        if (shipping_method.length <= 0) {
            console.log("shipping_method selected");
            $error = true;
        }
        console.log("shipping-method-wrapper"+shipping_method);

        /*if($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group input[type=radio]:checked').val() == undefined ) {
            $error = true;
            console.log("shipping-method-wrappercer not selected");
        }*/
        var note =  encodeURIComponent($('textarea[name=dropoff_notes-<?php echo $os["store_id"] ?>]').val());
        appendDataToSend += '&dropoff_notes['+<?php echo $os['store_id'] ?>+']='+note;

    <?php endforeach; ?>


    var sendData = $('#place-order-form').serialize() + '&dropoff_notes=' + dropoff_notes+appendDataToSend;
    console.log('sendData');
    console.log(sendData);
    if (!$error) {

        $valid_address = 0;
        $.ajax({
            url: 'index.php?path=checkout/confirm/multiStoreIndex',
            type: 'post',
            data: sendData,
            dataType: 'html',
            cache: false,
            async: false,
            success: function(json) {
                console.log("json");
                console.log(json);
                $('#confirm-order').css({ 'display': "none" });
                $('#confirm-order').attr('id', 'new-confirm-order');
                /*$('#confirm-order').remove(); */
                $('#pay-confirm-order').html(json);
                $('#pay-confirm-order').removeAttr('style');
                
                return true;
                    //window.location = json.redirect;
                },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $('#button-confirm').button('reset');
                return false;
            }
        });

        return true;

        /*if ($valid_address) {
            return true;
        }*/

    } else {
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
        $('#button-confirm').button('reset');
        return false;
    }
}

function saveInAddressBook() {

    console.log("saveInAddressBook");
    $('.alert').remove();
    $('#save-address').button('saving');

    $('.help-block').hide();
    $('.has-error').removeClass('has-error');

    $error = false;
    //var shipping_address = $('input[name="modal_address_street"]').val();
    var shipping_zipcode = $('input[name="shipping_zipcode"]').val();
    var shipping_city_id = $('input[name="shipping_city_id"]').val();
    var landmark = $('input[name="modal_address_locality"]').val();
    var building_name = $('input[name="modal_address_name"]').val();
    var flat_number = $('input[name="modal_address_flat"]').val();
    var address_type = $('input[name="modal_address_type"]:checked').val();
    //validate all fields

    if (landmark.length <= 0) {
        $error = true;
        $('input[name="modal_address_locality"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }
    if (building_name.length <= 0) {
        $error = true;
        $('input[name="modal_address_name"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }
    if (flat_number.length <= 0) {
        $error = true;
        $('input[name="modal_address_flat"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }
    if (address_type.length <= 0) {
        $error = true;
        $('input[name="modal_address_type"]').parents('.form-group').addClass('has-error').find('.help-block').show();
    }

    console.log(landmark+"**"+building_name+"**"+flat_number+"**"+address_type);
    
    if (!$error) {

        $valid_address = 0;
        $.ajax({
            url: 'index.php?path=checkout/address/addInAddressBook',
            type: 'post',
            async: false,
            data: $('#new-address-form').serialize(),
            dataType: 'json',
            cache: false,
            success: function(json) {

                console.log(json);
                console.log("checkout address add success");
                if (json.status == 0) {
                    $('#address-message').html(json['message']);
                    $('#address-success-message').html('');

                    return false;
                } else {
                    $('input[name="shipping_address_id"]').val(json.address_id);
                    $('#address-panel').html(json.html);
                    $('#addressModal').modal('hide');
                    $('.close').click();
                    return false;
                    //console.log("address add success else after return");

                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('#button-confirm').button('reset');
                return false;
            }
        });
        console.log("return false");
        return true;
    } else {
        /*$('html, body').animate({
            scrollTop: 0
        }, 'slow');
        $('#button-confirm').button('reset');*/
        return false;
    }
}
    $('.check-change').keyup(function(){
      //your stuff
      console.log("in change");
        $('#save-address').prop('disabled', false);
    });

    $(document).delegate('#open-address', 'click', function() {
        $('input[name="shipping_address_id"]').val($(this).attr('data-address-id'));
        console.log("address id selected"+$(this).attr('data-address-id'));        
        
        $.ajax({
            url: 'index.php?path=checkout/confirm/setAddressIdSession',
            type: 'post',
            async: true,
            data: {'shipping_address_id' : $(this).attr('data-address-id') },
            dataType: 'json',
            success: function(json) {
                console.log("address selected");
                $('#select-address').html(json['address']);

                <?php 

                foreach ($store_data as $os): 
                ?>
                    console.log("call to loadShippingMethods");
                    loadShippingMethods('<?php echo $os["store_id"] ?>'); 
                    
                <?php
                endforeach;?>

                $('#step-2').addClass('checkout-step-color');


                $('#delivery-option').click();

            }
        });

        
        //$(this).css({'background-color' : "green",'border-color' : "green"});
    });
    $(document).delegate('#dates_selected', 'click', function() {
        $('input[name="dates_selected"]').val($(this).attr('data-value'));
        console.log("address id selected"+$(this).attr('data-value'));
    });

    // $(document).delegate('#time_selected', 'click', function() {
    //     console.log($(this));
    //     console.log("time id selected"+$(this).attr('data-value'));
    //     console.log("time id selected"+$(this).attr('data-date'));
    // });

    $(document).delegate('#confirm-order', 'click', function() {
        console.log("order confirm click");

        var text = $('.confirm-order-text').html();
        console.log(text);
        $('.confirm-order-text').html('');
        $('.confirm-order-loader').show();
        setTimeout(function(){saveOrder();},200);
        
    });

</script>
<script type="text/javascript">

    $('.date-dob').datepicker({
        pickTime: false,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
        autoclose: true,
    });

    /*jQuery(function($){
        console.log("mask");
       $("#phone_number").mask("<?= $telephone_mask_number ?>",{autoclear:false,placeholder:"<?= $telephone_mask ?>"});
    });*/

    /*jQuery(function($) {
        console.log(" fax-number mask");
       $("#fax-number").mask("<?= $taxnumber_mask_number ?>",{autoclear:false,placeholder:"<?= $taxnumber_mask ?>"});
    });*/
    
    $(document).delegate('#checkoutLogout', 'click', function() {
        console.log("checkout lohout click");

        $.ajax({
            url: 'index.php?path=account/logout/checkoutLogout',
            type: 'post',
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json['status']) {
                    //location = json['redirect'];
                    window.location.reload(false);
                } else {
                }
            }
        });
    });
    

    $(document).delegate('#timeslot-next', 'click', function() {
        $('#step-3').addClass('checkout-step-color');
        console.log("timeslot-next click");

        $('#timeslot-next').html('<center><div class="login-loader" style=""></div></center>');

        var bothExpress = true;

        <?php foreach ($store_data as $os):  ?>
            var shipping_method = $('input[name=\'shipping_method-'+<?php echo $os['store_id'] ?>+'\']:checked').attr('value');

            console.log("shipping_method"+shipping_method);
            
            if (shipping_method != 'express.express') {
                bothExpress = false;
            }

        <?php endforeach; ?>

        console.log(bothExpress);
    
        if(bothExpress) {

            console.log('both express');
            

            <?php if($checkout_question_enabled) { ?>
                $('#timeslot-next-hidden').attr("href","#collapseQuestion");    
            <?php } else { ?>
                $('#timeslot-next-hidden').attr("href","#collapseFour");
            <?php } ?>

            $('#delivery_time_panel_link').attr("href","");            

        } else {
            $('#timeslot-next-hidden').attr("href","#collapseThree");
            $('#delivery_time_panel_link').attr("href","#collapseThree");
        }

        var p = saveOrder();

        console.log("timeslot  next saveOrder");
        
        $('#timeslot-next').html('<?= $text_next?>');
        
        $('#timeslot-next-hidden').click();

        $('#delivery_option__panel_link').attr("href","#collapseDeliveryOptions");
    });

    $(document).delegate('#payment-next', 'click', function() {
        $('#step-4').addClass('checkout-step-color');
        console.log("payment-next click");

        $('#payment-next').html('<center><div class="login-loader" style=""></div></center>');

        $error = false;

        <?php foreach ($store_data as $os):  ?>
            var shipping_method = $('input[name=\'shipping_method-'+<?php echo $os['store_id'] ?>+'\']:checked').attr('value')
            if (shipping_method.length <= 0) {
                console.log("shipping_method not selected");
                $error = true;
            }
            console.log("shipping-method-wrapper"+shipping_method);

            //console.log($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group input[type=radio]:checked').val());

            if($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group').length) {
                if($('#delivery-time-wrapper-<?php echo $os["store_id"] ?> ul.list-group input[type=radio]:checked').val() == undefined ) {
                    $error = true;
                    console.log("shipping-method-wrappercer not selected");
                }    
            } else {
                // shipping is express
            }
            


        <?php endforeach; ?>

        if (!$error) {
            $('#delivery-time-wrapper').click();
            var p = saveOrder();

            console.log("asae order pay next");
            console.log(p);
            if(p) {
                $('#payment-next').html('<?= $text_next?>');
            }
            
        } else {

            $('#payment-next').attr('disabled','disabled');

            $('#payment-next').html('<?= $text_next?>');
        }
        
    });

    $(document).delegate('.question-inputs', 'click', function() {
        console.log("validate question payment-next click");

        $error = false;

        <?php foreach ($questions as $question):  ?>
            var questionRes = $('input[name=\'question-'+<?php echo $question['checkout_question_id'] ?>+'\']').is(':checked');
            if (!questionRes) {
                console.log("questionRes not selected");
                $error = true;
            }
            console.log("shipping-method-wrapper"+questionRes);

        <?php endforeach; ?>

        if (!$error) {
            $('#question-payment-next').removeAttr('disabled');
            $('#question-payment-next').removeClass('btn-grey');
            $('#question-payment-next').addClass('btn-default');

            
        } else {

            $('#question-payment-next').attr('disabled','disabled');
            $('#question-payment-next').addClass('btn-grey');

            $('#question-payment-next').html('<?= $text_next?>');
        }
        
    });

    $(document).delegate('#question-payment-next', 'click', function() {
        console.log("question payment-next click");

        //$('#question-payment-next').html('<center><div class="login-loader" style=""></div></center>');

        $error = false;
        var sendData = {};

        <?php foreach ($questions as $question):  ?>
            var questionRes = $('input[name=\'question-'+<?php echo $question['checkout_question_id'] ?>+'\']').is(':checked');
            if (!questionRes) {
                console.log("questionRes not selected");
                $error = true;
            } else{
                
                sendData[<?php echo $question['checkout_question_id'] ?>] = $('input[name=\'question-'+<?php echo $question['checkout_question_id'] ?>+'\']:checked').attr('value');
                //sendData.push(obj);
            }
        <?php endforeach; ?>


        if (!$error) {

            console.log(sendData);

            dataSend = {
                data : sendData
            };
        
            $.ajax({
                url: 'index.php?path=checkout/checkout/saveQuestionResponse',
                type: 'post',
                data: dataSend,
                dataType: 'json',
                cache: false,
                async: false,
                beforeSend: function() {

                },
                success: function(json) {
                    console.log(json);
                    if (json['status']) {
                        var p = saveOrder();
                        $('#question-next-button').click();

                        console.log("asae order pay next");
                        console.log(p);
                        if(p) {
                            $('#question-payment-next').html('<?= $text_next?>');
                        }             
                    } else {
                        $('#question-payment-next').html('<?= $text_next?>');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });



    $(document).delegate('#headingDeliveryOptions', 'click', function() {
        console.log("headingDeliveryOptions click");
        $('#timeslot-next-hidden').attr("href","#collapseThree");
        $('#delivery_time_panel_link').attr("href","");
    });

    $(document).delegate('#promo-form-button', 'click', function() {
        console.log("promo-form-button click");
        $.ajax({
            url: 'index.php?path=checkout/coupon/coupon',
            type: 'post',
            data: $('.promo-form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json['status']) {
                    $('.promo-code-message').html('');
                    $('.promo-code-success-message').html(json['message']);
                    loadTotals($('input#shipping_city_id').val());
                    //setTimeout(function(){ window.location.reload(false); }, 1000);
                    
                } else {
                    $error = '';
                    if(json['error']){
                        $error += json['error'];
                    }
                    $('.promo-code-message').html($error);
                }
            }
        });
    });

    $(document.body).on('mousedown', '.pac-container .pac-item', function(e) {
        console.log('click fired');
        $('#locateme').removeClass('disabled');
    });

    $(document.body).on('change', '.LocalityId', function(e) {
        console.log('change LolityId checkout page');

        var address= $('#us1').locationpicker('location');
        console.log(address);

        /*if(address.addressComponents.streetName && address.addressComponents.streetNumber) {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        } else {
            $('#street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
            $('#edit-street').val(address.addressComponents.streetNumber+' '+address.addressComponents.streetName);
        }*/
        

        if(!$('.LocalityId').val().length) {
            $('#locateme').addClass('disabled');
        }
        
    });

    $('#accordion').on('shown.bs.collapse', function (e) {
        //after menu opens
        console.log("target"+e.target.id);
        if(e.target.id == "collapseTwo") {
            $('.checkoutChangeButton').hide();
            $('.checkoutChangeTimeButton').hide();
            $('.checkoutDeliveryOptionsChangeButton').hide();

            $('#step-3').removeClass('checkout-step-color');
            $('#step-4').removeClass('checkout-step-color');
            $('#step-5').removeClass('checkout-step-color');
        }


        if(e.target.id == "collapseThree") {
            $('.checkoutChangeTimeButton').hide(); 
            $('.checkoutDeliveryOptionsChangeButton').show();
            $('#step-4').removeClass('checkout-step-color');
            $('#step-5').removeClass('checkout-step-color');
        }

        if(e.target.id == "collapseFour") {
            $('.checkoutDeliveryOptionsChangeButton').show();
        }

        
    });

    $('#accordion').on('hidden.bs.collapse', function (e) {
        //after menu closes
        console.log("menu close target"+e.target.id);
        if(e.target.id == "collapseTwo") {
            $('.checkoutChangeButton').show();
        }

        if(e.target.id == "collapseThree") {
            $('.checkoutChangeTimeButton').show();
            $('.checkoutDeliveryOptionsChangeButton').show();
        }

        if(e.target.id == "collapseFour") {
            $('.checkoutChangeTimeButton').hide();
            $('.checkoutDeliveryOptionsChangeButton').hide();
        }
    });


</script>
<script src="https://api-test.equitybankgroup.com/js/eazzycheckout.js"></script>
</body>

</html>


