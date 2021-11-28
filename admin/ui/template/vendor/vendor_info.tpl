<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
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

        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($error) { ?>
        <div class="alert alert-danger"><i class="fa fa-check-circle"></i> <?php echo $error; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-general" data-toggle="tab"><?= $tab_general ?></a></li>
                    <li><a href="#tab-orders" style="display:none;" data-toggle="tab"><?= $tab_orders ?></a></li>
                    <li><a href="#tab-stores" data-toggle="tab"><?= $tab_stores ?></a></li>
                    <li><a href="#tab-vendor" data-toggle="tab"><?= $tab_vendor_data ?></a></li>
                    <?php if($package){ ?>
                    <li><a href="#tab-package" data-toggle="tab"><?= $tab_package_info ?></a></li>
                    <?php } ?>
                    <li><a href="#tab-graph" data-toggle="tab"><?= $tab_statistics ?></a></li>
                    <li><a href="#tab-credit" data-toggle="tab"><?= $tab_wallet ?></a></li>
                    <!-- <li><a href="#tab-subaccount" data-toggle="tab"><?= $tab_subaccount ?></a></li> -->
                    
                </ul>
                <div class="tab-content">

                <?php if($package){ ?>
                <div class="tab-pane" id="tab-package">
                    <table class="table table-bordered">
                        <tr>
                            <td><b><?= $entry_name ?></b></td>
                            <td><?= $package['name'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $entry_priority ?></b></td>
                            <td><?= $package['priority'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $entry_activation_date ?></b></td>
                            <td><?= date('d-m-Y', strtotime($package['date_start'])) ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $entry_active_upto_date ?></b></td>
                            <td><?= date('d-m-Y', strtotime($package['date_end'])) ?></td>
                        </tr>
                    </table>
                </div>
                <?php } ?>

                    <div class="tab-pane active in" id="tab-general">
                    <table class="table table-bordered">
                        <tr>
                            <td><b><?php echo $entry_username; ?></b></td>
                            <td><?= $user['username'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?php echo $entry_firstname; ?></b></td>
                            <td><?= $user['firstname'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?php echo $entry_lastname; ?></b></td>
                            <td><?= $user['lastname'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?php echo $entry_email; ?></b></td>
                            <td><?= $user['email'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $entry_ip_address ?></b></td>
                            <td><?= $user['ip'] ?></td>
                        </tr>
                        <tr>
                            <td><b><?= $entry_status ?></b></td>
                            <td>
                                <?php if($user['status']){ ?>
                                <?= $text_enabled ?>
                                <?php }else{ ?>
                                <?= $text_disabled ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><b><?= $entry_date_added ?></b></td>
                            <td><?= date('d-m-Y',strtotime($user['date_added'])) ?></td>
                        </tr>
                    </table>
                </div>  
                <div class="tab-pane" id="tab-orders" style="display:none;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                               
                                <td class="right"><?= $column_order_id ?></td>
                                <td class="left"><?= $column_customer ?></td>
                                <td class="left"><?= $column_status ?></td>
                                <td class="left"><?= $column_date_added ?></td>
                                <td class="right"><?= $column_total ?></td>
                                <td class="right"><?= $column_action ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($orders) { ?>
                            <?php foreach ($orders as $order) { ?>
                            <tr>
                                
                                <td class="right"><?php echo $order['order_id']; ?></td>
                                <td class="left"><?php echo $order['customer']; ?></td>
                                <td class="left"><?php echo $order['status']; ?></td>
                                <td class="left"><?php echo $order['date_added']; ?></td>
                                <td class="right"><?php echo $order['total']; ?></td>
                                <td class="right">                                    
                                    <a href="<?php echo $order['info']; ?>" data-toggle="tooltip" title="Info" class="btn btn-info">
                                        <i class="fa fa-info"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="center" colspan="6"> <?= $text_empty ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="pagination"><?php echo $pagination; ?></div>  

                </div>  
                <div class="tab-pane" id="tab-vendor">
                    <table class="table table-bordered">

                        <?php if(strtotime($user['free_to']) <= time()){ ?> 
                        <tr>
                            <td><b><?= $entry_commision ?></b></td>
                            <td><?= $user['commision'] ?>%</td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td><b><?= $entry_account ?></b></td>
                            <td><?= date('d-m-Y', strtotime($user['free_to'])) ?></td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td><b><?= $entry_business ?></b></td>
                            <td><?= $user['business'] ?></td>
                        </tr>

                        <tr>
                            <td><b><?= $entry_type ?></b></td>
                            <td><?= $user['type'] ?></td>
                        </tr>


                        <tr>
                            <td><b><?= $entry_tin_no ?></b></td>
                            <td><?= $user['tin_no'] ?></td>
                        </tr>

                        <tr>
                            <td><b><?= $entry_mobile ?></b></td>
                            <td><?= $user['mobile'] ?></td>
                        </tr>

                        <tr>
                            <td><b><?= $entry_telephone ?></b></td>
                            <td><?= $user['telephone'] ?></td>
                        </tr>

                        <tr>
                            <td><b><?= $entry_address ?></b></td>
                            <td><?= $user['address'] ?></td>
                        </tr>

                        <tr>
                            <td><b><?= $entry_store_name ?></b></td>
                            <td><?= $user['store_name'] ?></td>
                        </tr>
                    </table>
                </div>  
                <div class="tab-pane" id="tab-stores">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td><?= $entry_name ?></td>
                                <td><?= $entry_address ?></td>
                                <td><?= $entry_zipcode ?></td>
                                <td><?= $entry_type ?></td>
                                <td><?= $entry_date_added ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($stores as $store){ ?>
                            <tr>
                                <td><?= $store['name'] ?></td>
                                <td><?= $store['address'] ?></td>
                                <td><?= $store['zipcode'] ?></td>
                                <td>
                                    <?php foreach($store['categories'] as $cat){ ?>
                                    - <?= $cat['name'] ?><br />
                                    <?php } ?>
                                </td>
                                <td><?= date('d-m-Y', strtotime($store['date_added'])) ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tab-credit">
                    <form method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
                        <div id="credit"></div>
                        <br />
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-amount"><?= $entry_order_id ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="order_id" value="" placeholder="Order ID" id="input-order_id" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-credit-description"><?php echo $entry_description; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-credit-description" class="form-control" />
                            </div>
                        </div>                    
                        <div class="text-right">
                            <button type="button" id="button-credit" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_credit_add; ?></button>
                        </div> -->
                    </form>
                </div>
                <div class="tab-pane" id='tab-graph'>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                            <div class="panel-heading"><?= $text_heading ?></div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td><?= $column_completed_orders ?></td>
                                            <td><?= $column_selling ?></td>
                                            <td><?= $column_commision ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $statistics['orders'] ?></td>
                                            <td><?= $statistics['selling'] ?></td>
                                            <td><?= $statistics['commision'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                        <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                 <i class="fa fa-bar-chart-o"></i> <?= $text_orders ?>
                                <div class="pull-right" id="chart-date-range">
                                    <div id="block-range" class="btn-group">
                                        <li class="btn btn-default active" id="day"><?php echo $text_day; ?></li>
                                        <li class="btn btn-default" id="month"><?php echo $text_month; ?></li>
                                        <li class="btn btn-default " id="year"><?php echo $text_year; ?></li>
                                    </div>
                                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 0px 10px; border: 1px solid #ccc; font-weight: normal;">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        <span></span> <b class="caret"></b>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div style="display: block;" id="chart-orders" class="chart "></div>                                
                            </div>
                        </div>
                    </div>
                    </div>
                </div><!-- END tab-graph -->

                
                <div class="tab-pane" id="tab-subaccount">
                    
                    <?php if($verification_sent) { ?>

                            <table class="table table-bordered">
                                <tr>
                                    <td><b><?= $entry_account_id ?> </b></td>
                                    <td><?= $verification_status['id'] ?></td>
                                </tr>
                                <tr>
                                    <td><b><?= $entry_name ?></b></td>
                                    <td><?= $verification_status['name'] ?></td>
                                </tr>
                                <tr>
                                    <td><b><?= $entry_can_receive ?> </b></td>
                                    <td><?= $verification_status['can_receive?'] ?></td>
                                </tr>
                                <tr>
                                    <td><b><?= $entry_is_verified ?>  </b></td>
                                    <td><?= $verification_status['is_verified?'] ?></td>
                                </tr>
                                <tr>
                                    <td><b><?= $entry_last_verification_request_status ?></b></td>
                                    <?php if($verification_status['is_verified?']) { ?>
                                        <td style="color: green;"><?= $verification_status['last_verification_request_status'] ?></td>

                                    <?php } else { ?>

                                        <td style="color: blue;"><?= $verification_status['last_verification_request_status'] ?></td>

                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td><b><?= $entry_balance ?></b></td>
                                    <td><?= $verification_status['balance'] ?></td>
                                </tr>
                                <tr>
                                    <td><b><?= $entry_balance_available_for_withdraw ?>  </b></td>
                                    <td><?= $verification_status['balance_available_for_withdraw'] ?></td>
                                </tr>
                            </table>

                            <table class="table table-bordered">
                                <th> Bank Information </th>
                                <?php foreach($verification_status['informations'] as $info) { ?>
                                    <tr>
                                        <td><b><?= $info['key'] ?> </b></td>
                                        <td><?=  $info['value'] ?></td>
                                    </tr>
                                <?php } ?>
                            </table>

                            <table class="table table-bordered">
                                <th> Configuration </th>
                                    
                                <tr>
                                    <td><b><?= $entry_auto_withdraw ?>  </b></td>
                                    <td><?= $verification_status['configuration']['auto_withdraw'] ?></td>
                                </tr>

                                <tr>
                                    <td><b><?= $entry_commission_percent ?>  </b></td>
                                    <td><?= $verification_status['configuration']['commission_percent'] ?></td>
                                </tr>

                                <tr>
                                    <td><b><?= $entry_payment_email_notification ?>  </b></td>
                                    <td><?= $verification_status['configuration']['payment_email_notification'] ?></td>
                                </tr>
                            </table>
                    <?php } else { ?>

                            <?php if ($subaccount_created) { ?>
                            
                                <form action="<?php echo $verify_subaccount_action; ?>" method="post" enctype="multipart/form-data" id="form-verifysubaccount" class="form-horizontal"> 
                                    
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_price_range; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="price_range" value="<?php echo $price_range; ?>" placeholder="Entre R$ 50,00 e R$ 500,00" id="input-price_range" class="form-control" />
                                          <?php if ($error_price_range) { ?>
                                          <div class="text-danger"><?php echo $error_price_range; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_physical_product; ?></label>
                                        <div class="col-sm-10">
                                            <input type="radio" name="physical_products" value="true" checked="checked" /> <?= $text_yes ?> 
                                            <input type="radio" name="physical_products" value="false"/> <?= $text_no ?> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_business_type; ?></label>
                                        <div class="col-sm-10">
                                          <textarea type="text" name="business_type" value="<?php echo $business_type; ?>" placeholder="<?php echo $entry_business_type; ?>" id="input-business_type" class="form-control" ></textarea>
                                        </div>
                                    </div>                        
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-automatic_transfer"><?php echo $entry_automatic_transfer; ?></label>
                                        <div class="col-sm-10">
                                          
                                            <input type="radio" name="automatic_transfer"  value="true" checked="checked" /> <?= $text_yes ?> 
                                           
                                            <input type="radio" name="automatic_transfer" value="false"/> <?= $text_no ?> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-newsletter"><?php echo $entry_person_type; ?></label>
                                        <div class="col-sm-10">
                                          <select name="person_type" id="input-cpf" class="form-control">
                                            <option value="Pessoa Física" selected="selected"><?php echo $text_individual; ?></option>
                                            <option value="Pessoa Jurídica"><?php echo $text_joined; ?></option>
                                          </select>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_bank; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="bank" value="<?php echo $bank; ?>" placeholder="<?php echo $entry_bank; ?>" id="input-bank" class="form-control" />
                                          <?php if ($error_bank) { ?>
                                          <div class="text-danger"><?php echo $error_bank; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_cpf; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="cpf" value="<?php echo $cpf; ?>" placeholder="<?php echo $entry_cpf; ?>" id="input-cpf" class="form-control" />
                                          <?php if ($error_cpf) { ?>
                                          <div class="text-danger"><?php echo $error_cpf; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_bank_cc; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="bank_cc" value="<?php echo $bank_cc; ?>" placeholder="<?php echo $entry_bank_cc; ?>" id="input-bank_cc" class="form-control" />
                                          <?php if ($error_bank_cc) { ?>
                                          <div class="text-danger"><?php echo $error_bank_cc; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_name; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" disabled/>
                                          <input type="hidden" name="vendor_id" value="<?= $vendor_id ?>" />
                                          <?php if ($error_name) { ?>
                                          <div class="text-danger"><?php echo $error_name; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_address; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="address" value="<?php echo $address; ?>" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control" disabled/>
                                          <?php if ($error_address) { ?>
                                          <div class="text-danger"><?php echo $error_address; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_cep; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="cep" value="<?php echo $cep; ?>" placeholder="<?php echo $entry_cep; ?>" id="input-cep" class="form-control"/>
                                          <?php if ($error_cep) { ?>
                                          <div class="text-danger"><?php echo $error_cep; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_city; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control"/>
                                          <?php if ($error_city) { ?>
                                          <div class="text-danger"><?php echo $error_city; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_state; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="state" value="<?php echo $state; ?>" placeholder="<?php echo $entry_state; ?>" id="input-state" class="form-control"/>
                                          <?php if ($error_state) { ?>
                                          <div class="text-danger"><?php echo $error_state; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_telephone; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" disabled/>
                                          <?php if ($error_telephone) { ?>
                                          <div class="text-danger"><?php echo $error_telephone; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-newsletter"><?php echo $entry_account_type; ?></label>
                                        <div class="col-sm-10">
                                          <select name="account_type" id="input-account_type" class="form-control">
                                            <option value="Corrente" selected="selected"><?php echo $text_credit; ?></option>
                                            <option value="Poupança"><?php echo $text_saving; ?></option>
                                          </select>
                                        </div>
                                      </div>

                                      <div class="form-group required">
                                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_bank_ag; ?></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="bank_ag" value="<?php echo $bank_ag; ?>" placeholder="<?php echo $entry_bank_ag; ?>" id="input-bank_ag" class="form-control" />
                                          <?php if ($error_bank_ag) { ?>
                                          <div class="text-danger"><?php echo $error_bank_ag; ?></div>
                                          <?php  } ?>
                                        </div>
                                    </div>

                                    <button type="button" id="verify-sub-account-button" class="btn btn-primary"> <?= $text_verify_subaccount?></button>
                                </form>
                            <?php } else { ?>

                                <form action="<?php echo $subaccount_action; ?>" method="post" enctype="multipart/form-data" id="form-subaccount" class="form-horizontal"> 
                                    
                                    <?php if(!$this->user->isVendor()) { ?>

                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label" for="input-name"><?= $text_vendor ?></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="vendor_name" value="<?php echo $vendor_name; ?>" placeholder="Vendor name" class="form-control" disabled/>
                                                <input type="hidden" name="vendor_id" value="<?= $vendor_id ?>" />
                                                <input type="hidden" name="vendor_name" value="<?= $vendor_name ?>" />
                                                <?php if ($error_vendor_id) { ?>
                                                <div class="text-danger"><?php echo $error_vendor_id; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <?php if (!$this->user->isVendor()): ?>
                                            
                                        
                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label" for="input-commision"><?php echo $entry_commision; ?></label>
                                            <div class="col-sm-10">
                                                <input type="number" name="commision" id="commision" value="<?php echo $subaccount_commision; ?>" placeholder="<?php echo $entry_commision; ?>" id="input-commision" class="form-control" />
                                                <?php if ($error_commision) { ?>
                                                <div class="text-danger"><?php echo $error_commision; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <?php endif ?>

                                    <?php } ?>

                                    <button type="button" id="sub-account-button" class="btn btn-primary"> <?= $text_create_subaccount?></button>
                                </form>

                            <?php } ?>

                    <?php } ?>
                    
                
                </div>
            </div><!-- END .tab-content -->
        </div><!-- END .panel-body -->
    </div><!-- END .panel -->
</div><!-- END .container-fluid -->
</div><!-- END #content -->

<link type="text/css" href="ui/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<script type="text/javascript" src="ui/javascript/jquery/daterangepicker/moment.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="ui/javascript/jquery/flot/jquery.flot.tickrotor.js"></script>

<script type="text/javascript"><!--
    
$('#credit').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#credit').load(this.href);
});

$('#credit').load('index.php?path=vendor/vendor/credit&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>');

$('#sub-account-button').on('click', function(e) {
    e.preventDefault();
    
    $('#form-subaccount').submit();
    /*$.ajax({
        url: 'index.php?path=vendor/vendor/subaccount&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>&commision='+commision,
        type: 'post',
        dataType: 'html',
        beforeSend: function() {
            $('#button-credit').button('loading');
        },
        complete: function() {
            $('#button-credit').button('reset');
        },
        success: function(html) {
            $('.alert').remove();

            $('#credit').html(html);

            $('#tab-credit input[name=\'order_id\']').val('');
            $('#tab-credit input[name=\'amount\']').val('');
            $('#tab-credit input[name=\'description\']').val('');
        }
    });*/
});

$('#verify-sub-account-button').on('click', function(e) {
    e.preventDefault();
    
    $('#form-verifysubaccount').submit();
});

$('#button-credit').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?path=vendor/vendor/credit&token=<?php echo $token; ?>&vendor_id=<?php echo $vendor_id; ?>',
        type: 'post',
        dataType: 'html',
        data: 'description=' + encodeURIComponent($('#tab-credit input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-credit input[name=\'amount\']').val())+ '&order_id=' + encodeURIComponent($('#tab-credit input[name=\'order_id\']').val()),
        beforeSend: function() {
            $('#button-credit').button('loading');
        },
        complete: function() {
            $('#button-credit').button('reset');
        },
        success: function(html) {
            $('.alert').remove();

            $('#credit').html(html);

            $('#tab-credit input[name=\'order_id\']').val('');
            $('#tab-credit input[name=\'amount\']').val('');
            $('#tab-credit input[name=\'description\']').val('');
        }
    });
});


<?php if(isset($this->request->get['page'])){ ?>
    $(function(){
        $('a[href="#tab-orders"]').trigger('click');
    });
<?php } ?>


$('#block-range li').on('click', function(e) {
    e.preventDefault();

    $(this).parent().find('li').removeClass('active');
    $(this).addClass('active');

    block_range = $(this).attr('id');

    getCharts();
});

var start_date = '';
var end_date = '';
var block_range = 'day';

jQuery(document).ready(function() {


    var cb = function(start, end, label) { /* date range picker callback */
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        /* set global dates */
        start_date = start.format('YYYY-MM-DD');
        end_date = end.format('YYYY-MM-DD');
        /******************************************/

        getCharts();
    };

    var option_daterangepicker = {
        startDate: moment().subtract('days', 14),
        endDate: moment(),
        minDate: '01/01/2012',
        maxDate: '12/31/2050',
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Last 7 Days': [moment().subtract('days', 6), moment()],
            'Last 15 Days': [moment().subtract('days', 14), moment()],
            'Last 30 Days': [moment().subtract('days', 29), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    };

    jQuery('#reportrange span').html(moment().subtract('days', 14).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

    jQuery('#reportrange').daterangepicker(option_daterangepicker, cb);

    start_date  = option_daterangepicker.startDate.format('YYYY-MM-DD');
    end_date    = option_daterangepicker.endDate.format('YYYY-MM-DD')
    block_range = $('#block-range li.active').attr('id');

    getCharts();
});

function getCharts(){
    orders();
}

function orders() {
    $('#orders_score').html('<img src="ui/image/loader.gif">');
    $('#chart-orders').html('<div class="loading"><img src="ui/image/loader.gif"></div>');

    $.ajax({
        type: 'get',
        url: 'index.php?path=dashboard/charts/VendorOrders&vendor_id=<?= $user['user_id'] ?>&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
        dataType: 'json',
        success: function(json) {
            var option = {
                shadowSize: 0,
                lines: {
                    show: true
                },
                grid: {
                    backgroundColor: '#FFFFFF',
                    hoverable: true
                },
                points: {
                    show: true,
                    fillColor: '#5cb85c'
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis'],
                    rotateTicks : 45
                },
                yaxis : {
                    min: 0,
                    tickDecimals: 0
                }
            };

            json['order']['color'] = "#5cb85c";
            $.plot('#chart-orders', [json['order']], option);

            $('#chart-orders').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                    $('#tooltip').css({
                        position: 'absolute',
                        left: item.pageX - ($('#tooltip').outerWidth() / 2),
                        top: item.pageY - $('#tooltip').outerHeight(),
                        pointer: 'cusror'
                    }).fadeIn('slow');

                    $('#chart-orders').css('cursor', 'pointer');
                } else {
                    $('#chart-orders').css('cursor', 'auto');
                }
            });

            $('#orders_score').html(json['order']['total']);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

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