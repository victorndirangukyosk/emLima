<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-default"><i class="fa fa-print"></i></a> <a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-default"><i class="fa fa-truck"></i></a> 
                    
          <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-order" data-toggle="tab"><?php echo $tab_order; ?></a></li>
          <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
          <?php if ($shipping_method) { ?>
          <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
          <?php } ?>
          <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
          <?php if ($payment_action) { ?>
          <li><a href="#tab-action" data-toggle="tab"><?php echo $tab_action; ?></a></li>
          <?php } ?>
          <?php if ($maxmind_id) { ?>
          <li><a href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-order">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_order_id; ?></td>
                <td>#<?php echo $order_id; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_invoice_no; ?></td>
                <td><?php if ($invoice_no) { ?>
                  <?php echo $invoice_no; ?>
                  <?php } else { ?>
                  -
                  <?php } ?></td>
              </tr>
              <?php if ($customer) { ?>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td>
                    <?php if(!$this->user->isVendor()){ ?>
                        <a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> <?php echo $lastname; ?></a>
                    <?php }else{ ?>
                        <?php echo $firstname; ?> <?php echo $lastname; ?>                    
                    <?php } ?>
                </td>
              </tr>
              <?php } else { ?>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
              </tr>
              <?php } ?>
              <?php if ($customer_group) { ?>
              <tr>
                <td><?php echo $text_customer_group; ?></td>
                <td><?php echo $customer_group; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_email; ?></td>
                <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_telephone; ?></td>
                <td><?php echo $telephone; ?></td>
              </tr>
              <?php if ($fax) { ?>
              <tr>
                <td><?php echo $text_fax; ?></td>
                <td><?php echo $fax; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($account_custom_fields as $custom_field) { ?>
              <tr>
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_total; ?></td>
                <td><?php echo $total; ?></td>
              </tr>
              <?php if ($customer && $reward) { ?>
              <tr>
                <td><?php echo $text_reward; ?></td>
                <td><?php echo $reward; ?>
                  <?php if (!$reward_total) { ?>
                  <button id="button-reward-add" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
                  <?php } else { ?>
                  <button id="button-reward-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_reward_remove; ?></button>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php if ($order_status) { ?>
              <tr>
                <td><?php echo $text_order_status; ?></td>
                <td id="order-status"><?php echo $order_status; ?></td>
              </tr>
              <?php } ?>
              <?php if ($comment) { ?>
              <tr>
                <td><?php echo $text_comment; ?></td>
                <td><?php echo $comment; ?></td>
              </tr>
              <?php } ?>
              <?php if ($affiliate) { ?>
              <tr>
                <td><?php echo $text_affiliate; ?></td>
                <td><a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_commission; ?></td>
                <td><?php echo $commission; ?>
                  <?php if (!$commission_total) { ?>
                  <button id="button-commission-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> <?php echo $button_commission_add; ?></button>
                  <?php } else { ?>
                  <button id="button-commission-remove" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i> <?php echo $button_commission_remove; ?></button>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip) { ?>
              <tr>
                <td><?php echo $text_ip; ?></td>
                <td><?php echo $ip; ?></td>
              </tr>
              <?php } ?>
              <?php if ($forwarded_ip) { ?>
              <tr>
                <td><?php echo $text_forwarded_ip; ?></td>
                <td><?php echo $forwarded_ip; ?></td>
              </tr>
              <?php } ?>
              <?php if ($user_agent) { ?>
              <tr>
                <td><?php echo $text_user_agent; ?></td>
                <td><?php echo $user_agent; ?></td>
              </tr>
              <?php } ?>
              <?php if ($accept_language) { ?>
              <tr>
                <td><?php echo $text_accept_language; ?></td>
                <td><?php echo $accept_language; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_date_added; ?></td>
                <td><?php echo $date_added; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_date_modified; ?></td>
                <td><?php echo $date_modified; ?></td>
              </tr>
            </table>
          </div>
          <div class="tab-pane" id="tab-payment">
            <table class="table table-bordered">
              <?php foreach ($payment_custom_fields as $custom_field) { ?>
              <tr>
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><?php echo $text_payment_method; ?></td>
                <td><?php echo $payment_method; ?></td>
              </tr>
            </table>
          </div>
          <?php if ($shipping_method) { ?>
          <div class="tab-pane" id="tab-shipping">
            <table class="table table-bordered">
              <tr>
                <td><?= $entry_name ?></td>
                <td><?php echo $shipping_name; ?></td>
              </tr>
              <tr>
                <td><?= $entry_contact_no ?></td>
                <td><?php echo $shipping_contact_no; ?></td>
              </tr>
              <tr>
                <td><?= $entry_city ?></td>
                <td><?php echo $shipping_city; ?></td>
              </tr>
              <tr>
                <td><?= $entry_addresses ?></td>
                <td><?php echo $shipping_address; ?></td>
              </tr>              
              <?php foreach ($shipping_custom_fields as $custom_field) { ?>
              <tr>
                <td><?php echo $custom_field['name']; ?>:</td>
                <td><?php echo $custom_field['value']; ?></td>
              </tr>
              <?php } ?>
              <?php if ($shipping_method) { ?>
              <tr>
                <td><?php echo $text_shipping_method; ?></td>
                <td><?php echo $shipping_method; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <?php } ?>
          <div class="tab-pane" id="tab-product">
            
                <hr />
                
                <?php 
                
                $new_store=$i=0; $stores= array(); $store = array(); $store_id = -1; 
                
                foreach ($products as $product) { 
                                
                if(!$filter_stores ||  ($filter_stores && in_array($product['store_id'], $filter_stores))){
                   
                //display store data 
                if(!in_array($product['store_id'], $stores)){
                                
                    $stores[] = $product['store_id'];
                    $store_id = $product['store_id'];
                    
                    $new_store = 1;

                    $store_id = $product['store_id'];            
                    $store = $this->db->query('select * from '.DB_PREFIX.'vendor_order vo inner join '.DB_PREFIX.'store s on s.store_id = vo.store_id where vo.order_id="'.$order_id.'" and vo.store_id="'.$product['store_id'].'"')->row;
                    $store_total_data = $this->db->query('select * from '.DB_PREFIX.'vendor_order_total where order_id="'.$order_id.'" and store_id="'.$product['store_id'].'" order by sort_order ASC')->rows;                

                    //vendor commision 
                    $commision = round($store['total'] * $store['commision'] / 100, 2);
                    $vendor_total = $this->currency->format($store['total'] - $commision, $store['currency_code'], $store['currency_value']);
                    $commision = $this->currency->format($commision, $store['currency_code'], $store['currency_value']);

                ?>
                <table id="store_<?= $store_id ?>" class="store table table-bordered">
                <tr>
                    <td class="left">
                        <b><?= $entry_store ?></b>:
                        <span class="store_name"><?= $store['name'] ?></span></td>
                    <td class="left">
                        
                        <span style="line-height: 30px;">
                            <b><?= $entry_status ?></b>: 
                            <span class="status">
                            <?= $this->model_sale_order->getStatusNameById($store['order_status_id']) ?>
                            </span>
                        </span>
                        
                        <select style="width: 50%;float: right;" class="form-control" onchange="status(<?= $store_id ?>,this.value, $(this).find('option:selected').html());">
                            <?php foreach($order_statuses as $status){ ?>
                                <?php if($store['status'] == $status['name']) { ?>
                                    <option value="<?= $status['order_status_id'] ?>" selected><?= $status['name'] ?></option>
                                <?php }else{ ?>
                                    <option value="<?= $status['order_status_id'] ?>"><?= $status['name'] ?></option>
                                <?php } ?>    
                            <?php } ?>
                        </select>

                    </td>
                    <td class="left" colspan="2">                    
                        <b><?= $entry_payment_status ?></b>:
                        <span class="payment_status">
                        <?= $vendor_total ?>    
                        <?php if($store['payment_status']) { ?>
                            <span class="text"><?= $text_paid ?></span>
                            <?php if(!$this->user->isVendor()){ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" onclick="payment_status(<?= $store_id ?>,0);" class="btn btn-primary button"><?= $button_mark_unpaid ?></button>
                            <?php } ?>
                        <?php }else{ ?>
                            <span class="text"><?= $text_unpaid ?></span>
                            <?php if(!$this->user->isVendor()){ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" onclick="payment_status(<?= $store_id ?>,1);" class="btn btn-primary button"><?= $button_mark_paid ?></button>                        
                            <?php } ?>
                        <?php } ?>
                        </span>
                    </td> 
                    <td class="left">
                        <b><?= $entry_commision ?></b>:
                        <span class="commision">
                            <?= $commision.' ('.$store['commision'].'%)' ?>
                        </span>
                    </td>
                </tr>            
                <tr style="line-height: 40px;">
                    <td colspan='2'>
                        <b><?= $entry_expected_delivery_time ?></b>

                        <span class="delivery_time_data">
                            <?= $store['delivery_date'].' ('.$store['delivery_timeslot'].')' ?>
                        </span>

                    </td>
                    <td colspan='3'>                        
                        <b><?= $entry_change_delivery_time ?></b>                        
                        <input style="display: inline-block;width: auto;"  data-id="<?= $store['store_id'] ?>" type="text" name="delivery_date[<?= $store_id ?>]" class="form-control delivery_date datepicker" value="" data-date-format='YYYY-mm-dd' placeholder="Delivery date" />
                        <select style="display: inline-block;width: auto;"  name="delivery_time[<?= $store_id ?>]"  class="form-control delivery_timeslot"></select>
                    </td>
                </tr>
            </table>

            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="left"><?php echo $column_product; ?></td>
                  <td class="left"><?php echo $column_model; ?></td>
                  <td class="right"><?php echo $column_quantity; ?></td>
                  <td class="right"><?php echo $column_price; ?></td>
                  <td class="right"><?php echo $column_total; ?></td>
                </tr>
              </thead>
              <tbody>           
                
            <?php } ?><!-- END if new store data -->
            
            <!-- display products -->            
            <tr>
              <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } else { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                <?php } ?>
                <?php } ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
            </tr>

            <!-- display store total -->
            <?php  if(!isset($products[$i+1]) || $store_id != $products[$i+1]['store_id']){ 
            foreach($store_total_data as $st){ ?>
            <tr>
              <td colspan="4" class="text-right"><?php echo $st['title']; ?>:</td>
              <td class="text-right"><?php echo $st['text']; ?></td>
            </tr>
            <?php } ?>  
            
               </tbody>          
            </table>           
            
            <hr />
            
            <?php } ?><!-- END vendor order total -->
            
            <?php } ?><!-- END filter store  -->
            
            <?php $i++; } ?><!-- END foreach products -->
           
            
          </div>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
          </div>
          <?php if ($payment_action) { ?>
          <div class="tab-pane" id="tab-action"> <?php echo $payment_action; ?> </div>
          <?php } ?>
          <?php if ($maxmind_id) { ?>
          <div class="tab-pane" id="tab-fraud">
            <table class="table table-bordered">
              <?php if ($country_match) { ?>
              <tr>
                <td><?php echo $text_country_match; ?></td>
                <td><?php echo $country_match; ?></td>
              </tr>
              <?php } ?>
              <?php if ($country_code) { ?>
              <tr>
                <td><?php echo $text_country_code; ?></td>
                <td><?php echo $country_code; ?></td>
              </tr>
              <?php } ?>
              <?php if ($high_risk_country) { ?>
              <tr>
                <td><?php echo $text_high_risk_country; ?></td>
                <td><?php echo $high_risk_country; ?></td>
              </tr>
              <?php } ?>
              <?php if ($distance) { ?>
              <tr>
                <td><?php echo $text_distance; ?></td>
                <td><?php echo $distance; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_region) { ?>
              <tr>
                <td><?php echo $text_ip_region; ?></td>
                <td><?php echo $ip_region; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_city) { ?>
              <tr>
                <td><?php echo $text_ip_city; ?></td>
                <td><?php echo $ip_city; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_latitude) { ?>
              <tr>
                <td><?php echo $text_ip_latitude; ?></td>
                <td><?php echo $ip_latitude; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_longitude) { ?>
              <tr>
                <td><?php echo $text_ip_longitude; ?></td>
                <td><?php echo $ip_longitude; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_isp) { ?>
              <tr>
                <td><?php echo $text_ip_isp; ?></td>
                <td><?php echo $ip_isp; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_org) { ?>
              <tr>
                <td><?php echo $text_ip_org; ?></td>
                <td><?php echo $ip_org; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_asnum) { ?>
              <tr>
                <td><?php echo $text_ip_asnum; ?></td>
                <td><?php echo $ip_asnum; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_user_type) { ?>
              <tr>
                <td><?php echo $text_ip_user_type; ?></td>
                <td><?php echo $ip_user_type; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_country_confidence) { ?>
              <tr>
                <td><?php echo $text_ip_country_confidence; ?></td>
                <td><?php echo $ip_country_confidence; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_region_confidence) { ?>
              <tr>
                <td><?php echo $text_ip_region_confidence; ?></td>
                <td><?php echo $ip_region_confidence; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_city_confidence) { ?>
              <tr>
                <td><?php echo $text_ip_city_confidence; ?></td>
                <td><?php echo $ip_city_confidence; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_postal_confidence) { ?>
              <tr>
                <td><?php echo $text_ip_postal_confidence; ?></td>
                <td><?php echo $ip_postal_confidence; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_postal_code) { ?>
              <tr>
                <td><?php echo $text_ip_postal_code; ?></td>
                <td><?php echo $ip_postal_code; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_accuracy_radius) { ?>
              <tr>
                <td><?php echo $text_ip_accuracy_radius; ?></td>
                <td><?php echo $ip_accuracy_radius; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_net_speed_cell) { ?>
              <tr>
                <td><?php echo $text_ip_net_speed_cell; ?></td>
                <td><?php echo $ip_net_speed_cell; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_metro_code) { ?>
              <tr>
                <td><?php echo $text_ip_metro_code; ?></td>
                <td><?php echo $ip_metro_code; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_area_code) { ?>
              <tr>
                <td><?php echo $text_ip_area_code; ?></td>
                <td><?php echo $ip_area_code; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_time_zone) { ?>
              <tr>
                <td><?php echo $text_ip_time_zone; ?></td>
                <td><?php echo $ip_time_zone; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_region_name) { ?>
              <tr>
                <td><?php echo $text_ip_region_name; ?></td>
                <td><?php echo $ip_region_name; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_domain) { ?>
              <tr>
                <td><?php echo $text_ip_domain; ?></td>
                <td><?php echo $ip_domain; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_country_name) { ?>
              <tr>
                <td><?php echo $text_ip_country_name; ?></td>
                <td><?php echo $ip_country_name; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_continent_code) { ?>
              <tr>
                <td><?php echo $text_ip_continent_code; ?></td>
                <td><?php echo $ip_continent_code; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ip_corporate_proxy) { ?>
              <tr>
                <td><?php echo $text_ip_corporate_proxy; ?></td>
                <td><?php echo $ip_corporate_proxy; ?></td>
              </tr>
              <?php } ?>
              <?php if ($anonymous_proxy) { ?>
              <tr>
                <td><?php echo $text_anonymous_proxy; ?></td>
                <td><?php echo $anonymous_proxy; ?></td>
              </tr>
              <?php } ?>
              <?php if ($proxy_score) { ?>
              <tr>
                <td><?php echo $text_proxy_score; ?></td>
                <td><?php echo $proxy_score; ?></td>
              </tr>
              <?php } ?>
              <?php if ($is_trans_proxy) { ?>
              <tr>
                <td><?php echo $text_is_trans_proxy; ?></td>
                <td><?php echo $is_trans_proxy; ?></td>
              </tr>
              <?php } ?>
              <?php if ($free_mail) { ?>
              <tr>
                <td><?php echo $text_free_mail; ?></td>
                <td><?php echo $free_mail; ?></td>
              </tr>
              <?php } ?>
              <?php if ($carder_email) { ?>
              <tr>
                <td><?php echo $text_carder_email; ?></td>
                <td><?php echo $carder_email; ?></td>
              </tr>
              <?php } ?>
              <?php if ($high_risk_username) { ?>
              <tr>
                <td><?php echo $text_high_risk_username; ?></td>
                <td><?php echo $high_risk_username; ?></td>
              </tr>
              <?php } ?>
              <?php if ($high_risk_password) { ?>
              <tr>
                <td><?php echo $text_high_risk_password; ?></td>
                <td><?php echo $high_risk_password; ?></td>
              </tr>
              <?php } ?>
              <?php if ($bin_match) { ?>
              <tr>
                <td><?php echo $text_bin_match; ?></td>
                <td><?php echo $bin_match; ?></td>
              </tr>
              <?php } ?>
              <?php if ($bin_country) { ?>
              <tr>
                <td><?php echo $text_bin_country; ?></td>
                <td><?php echo $bin_country; ?></td>
              </tr>
              <?php } ?>
              <?php if ($bin_name_match) { ?>
              <tr>
                <td><?php echo $text_bin_name_match; ?></td>
                <td><?php echo $bin_name_match; ?></td>
              </tr>
              <?php } ?>
              <?php if ($bin_name) { ?>
              <tr>
                <td><?php echo $text_bin_name; ?></td>
                <td><?php echo $bin_name; ?></td>
              </tr>
              <?php } ?>
              <?php if ($bin_phone_match) { ?>
              <tr>
                <td><?php echo $text_bin_phone_match; ?></td>
                <td><?php echo $bin_phone_match; ?></td>
              </tr>
              <?php } ?>
              <?php if ($bin_phone) { ?>
              <tr>
                <td><?php echo $text_bin_phone; ?></td>
                <td><?php echo $bin_phone; ?></td>
              </tr>
              <?php } ?>
              <?php if ($customer_phone_in_billing_location) { ?>
              <tr>
                <td><?php echo $text_customer_phone_in_billing_location; ?></td>
                <td><?php echo $customer_phone_in_billing_location; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ship_forward) { ?>
              <tr>
                <td><?php echo $text_ship_forward; ?></td>
                <td><?php echo $ship_forward; ?></td>
              </tr>
              <?php } ?>
              <?php if ($city_postal_match) { ?>
              <tr>
                <td><?php echo $text_city_postal_match; ?></td>
                <td><?php echo $city_postal_match; ?></td>
              </tr>
              <?php } ?>
              <?php if ($ship_city_postal_match) { ?>
              <tr>
                <td><?php echo $text_ship_city_postal_match; ?></td>
                <td><?php echo $ship_city_postal_match; ?></td>
              </tr>
              <?php } ?>
              <?php if ($score) { ?>
              <tr>
                <td><?php echo $text_score; ?></td>
                <td><?php echo $score; ?></td>
              </tr>
              <?php } ?>
              <?php if ($explanation) { ?>
              <tr>
                <td><?php echo $text_explanation; ?></td>
                <td><?php echo $explanation; ?></td>
              </tr>
              <?php } ?>
              <?php if ($risk_score) { ?>
              <tr>
                <td><?php echo $text_risk_score; ?></td>
                <td><?php echo $risk_score; ?></td>
              </tr>
              <?php } ?>
              <?php if ($queries_remaining) { ?>
              <tr>
                <td><?php echo $text_queries_remaining; ?></td>
                <td><?php echo $queries_remaining; ?></td>
              </tr>
              <?php } ?>
              <?php if ($maxmind_id) { ?>
              <tr>
                <td><?php echo $text_maxmind_id; ?></td>
                <td><?php echo $maxmind_id; ?></td>
              </tr>
              <?php } ?>
              <?php if ($error) { ?>
              <tr>
                <td><?php echo $text_error; ?></td>
                <td><?php echo $error; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
 <script type="text/javascript"><!--

$('#history').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();
    $('#history').load(this.href);
});			

$('#history').load('index.php?path=sale/vendor_order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

//--></script>
</div>

<script src="ui/javascript/jquery/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<link href="ui/javascript/jquery/datepicker/css/datepicker.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
    
$(".datepicker" ).datepicker({
    format: 'yyyy-mm-dd', 
    startDate: '-0d'
}).on('changeDate', function(e){
    get_ts($(this).attr('data-id'), $(this).val());    
});    

//--></script>
  
<script>
    
    function status($store_id,$status_value, $status_text){
        if(confirm('Are You Sure?')){
            $.ajax({
		url: 'index.php?path=sale/vendor_order/status&token=<?php echo $token; ?>',
		type: 'post',
		data: 'status=' + $status_value + '&order_id=<?php echo $order_id; ?>&store_id='+$store_id,
		beforeSend: function() {
		},
		complete: function() {
			$('.attention').remove();
		},
		success: function(data) {
                    $('#store_'+$store_id+' .status').html($status_text);
		}
            });
        }
    }
    
    <?php if(!$this->user->isVendor()){ ?>
    function payment_status($store_id,$status){
        if(confirm('Are You Sure?')){
            $.ajax({
		url: 'index.php?path=sale/vendor_order/payment_status&token=<?php echo $token; ?>',
		type: 'post',
		data: 'status=' + $status + '&order_id=<?php echo $order_id; ?>&store_id='+$store_id,
		beforeSend: function() {
		},
		complete: function() {
			$('.attention').remove();
		},
		success: function(data) {
                    if($status==1){
			$('#store_'+$store_id+' .payment_status .text').html('Paid');
                        $('#store_'+$store_id+' .payment_status button').html('Mark Unpaid').attr('onclick',"payment_status("+$store_id+",0);");
                    }else{
                        $('#store_'+$store_id+' .payment_status .text').html('Unpaid');
                        $('#store_'+$store_id+' .payment_status button').html('Mark Paid').attr('onclick',"payment_status("+$store_id+",1);");
                    }
		}
            });
        }
    }
    <?php } ?>

function get_ts($store_id, $date){
    $.post('<?= HTTP_SERVER ?>index.php?path=sale/vendor_order/get_home_ts&token=<?= $token ?>',{ store_id: $store_id, date: $date }, function(data){
        $('#store_'+$store_id+' .delivery_timeslot').html(data);
    });
}

//save timeslot in store_id 
$('.delivery_timeslot').change(function(){
   
  $.post('<?= HTTP_SERVER ?>index.php?path=sale/vendor_order/save_timeslots&order_id=<?= $order_id ?>&token=<?= $token ?>', $('.delivery_date, .delivery_timeslot').serialize());    
  
  $html = $(this).parent().find('.delivery_date').val()+' ('+$(this).val()+')';
   
  $(this).parent().parent().find('.delivery_time_data').html($html);
   
});
</script>

<?php echo $footer; ?> 