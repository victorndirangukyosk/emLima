



<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash-o"></i></button>
        <button type="button" onclick="excel();" data-toggle="tooltip" title="" class="btn btn-success " data-original-title="Download Excel"><i class="fa fa-download"></i></button>
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
      <div class="pull-right">
            <button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
            
      </div>    
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">

           <div class="col-sm-3">
 <div class="form-group">
                                <label class="control-label" for="input-company">Company Name</label>
                                <input type="text" name="filter_company" value="<?php echo $filter_company; ?>" placeholder="Company Name" id="input-company" class="form-control" />
                            </div>

                        <div class="form-group">
                      <label class="control-label" for="input-parent-customer">Parent Customer Name</label>
                      <input type="text" name="filter_parent_customer" value="<?php if($filter_parent_customer != NULL && $filter_parent_customer_id != NULL) { echo $filter_parent_customer; } ?>" placeholder="<?php echo $entry_parent_customer; ?>" id="input-parent-customer" class="form-control" data-parent-customer-id="<?php if($filter_parent_customer != NULL && $filter_parent_customer_id != NULL) { echo $filter_parent_customer_id; } ?>" />
                  </div>     
           </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                <select name="filter_customer_group_id" id="input-customer-group" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <!-- <div class="form-group">
                <label class="control-label" for="input-approved"><?php echo $entry_approved; ?></label>
                <select name="filter_approved" id="input-approved" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_approved) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <?php } ?>
                  <?php if (!$filter_approved && !is_null($filter_approved)) { ?>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div> -->
             <div class="form-group">
                <label class="control-label" for="input-telephone"><?php echo $column_telephone; ?></label>
                <div class="input-group">
                <span class="input-group-btn">
                                        <button class="btn btn-default" type="button"><?php echo '+' . $this->config->get('config_telephone_code'); ?></button>                                      
                </span>
                <input type="text" name="filter_telephone" value="<?php echo $filter_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"  minlength="9" maxlength="9"/>
              </div>
              </div>

              <div class="form-group">
                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control" />
              </div>
            </div>
              <div class="col-sm-3">

              
                <div class="form-group">
                      <label class="control-label" for="input-account-manager-name">Account Manager Name</label>
                      <input type="text" name="filter_account_manager_name" value="<?php if($filter_account_manager_name != NULL && $filter_account_manager_id != NULL) { echo $filter_account_manager_name; } ?>" placeholder="<?php echo $entry_account_manager_name; ?>" id="input-account-manager-name" class="form-control" data-account-manager-id="<?php if($filter_account_manager_name != NULL && $filter_account_manager_id != NULL) { echo $filter_account_manager_id; } ?>" />
                </div>
                
                <div class="form-group">
                      <label class="control-label" for="input-customer-experience">Customer Experience</label>
                      <input type="text" name="filter_customer_experience" value="<?php if($filter_customer_experience != NULL && $filter_customer_experience_id != NULL) { echo $filter_customer_experience; } ?>" placeholder="<?php echo $entry_customer_experience; ?>" id="input-customer-experience" class="form-control" data-customer-experience-id="<?php if($filter_customer_experience != NULL && $filter_customer_experience_id != NULL) { echo $filter_customer_experience_id; } ?>" />
                </div>
                 
              </div>
             <div class="col-sm-3">
           
              <div class="form-group">
                <label class="control-label" for="input-date-added">Date Added From</label>
                <div class="input-group date" style="max-width: 321px;">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="Date Added From" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              
              <div class="form-group">
                <label class="control-label" for="input-payment-terms">Payment Terms</label>
                <select name="filter_payment_terms" id="input-payment-terms" class="form-control">
                            <option value=""></option>
                            <option value="Payment On Delivery" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == 'Payment On Delivery') { ?> selected="selected" <?php } ?> >Payment On Delivery</option>
                            <option value="7 Days Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == '7 Days Credit') { ?> selected="selected" <?php } ?> >7 Days Credit</option>
                            <option value="15 Days Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == '15 Days Credit') { ?> selected="selected" <?php } ?> >15 Days Credit</option>
                            <option value="30 Days Credit" <?php if (isset($filter_payment_terms) && !is_null($filter_payment_terms) && $filter_payment_terms == '30 Days Credit') { ?> selected="selected" <?php } ?> >30 Days Credit</option>
                </select>
              </div>   


             </div>

             
              <div class="col-sm-3">

               <div class="form-group">
                <label class="control-label" for="input-date-added-to">Date Added To</label>
                <div class="input-group date" style="max-width: 321px;">
                  <input type="text" name="filter_date_added_to" value="<?php echo $filter_date_added_to; ?>" placeholder="Date Added To" data-date-format="YYYY-MM-DD" id="input-date-added-to" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
               
               <div class="form-group">
                <label class="control-label" for="input-customer-price-category">Price Category</label>
                <select name="filter_customer_price_category" id="input-customer-price-category" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach ($price_categories as $category) { ?>
                            <?php if(isset($filter_customer_price_category) && ($filter_customer_price_category== $category['price_category'])){?>
                            <option selected="selected" value="<?php echo $category['price_category']; ?>"><?php echo $category['price_category']; ?></option>
                            <?php }else {?>
                             <option  value="<?php echo $category['price_category']; ?>"><?php echo $category['price_category']; ?></option>
                             <?php } ?>
                            <?php } ?>
                </select>
              </div>   
                  
                  
              </div>
              <div class="col-sm-3" style="margin-top:25px;">
              <div class="form-group">
                  <label><input type="checkbox" name="filter_sub_customer_show[]" value="<?php echo $filter_sub_customer_show; ?>" <?php if($filter_sub_customer_show == 1) { ?> checked="" <?php } ?>> Show Sub Customer </label>
              </div>
              
              <div class="form-group" style="margin-top:43px;">
               <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>  
              </div>
              </div>
              
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td style="width: 3px;" class="text-left"><?php if ($sort == 'c.email') { ?>
                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_telephone; ?></td>

                  <td style="width: 3px;" class="text-left"><?php if ($sort == 'customer_group') { ?>
                    <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left">Source</td>
                  <td class="text-left"><?php if ($sort == 'c.ip') { ?>
                    <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($customers) { ?>
                <?php foreach ($customers as $customer) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($customer['customer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $customer['name']; ?>
                   <br/> <?php echo $customer['company_name']; ?></td>
                  <td class="text-left"><?php echo $customer['email']; ?></td>
                  <td class="text-left"><?php echo $customer['telephone']; ?></td>
                  <td class="text-left"><?php echo $customer['customer_group']; ?></td>
                  <td class="text-left"><?php echo $customer['status']; ?></td>
                  <td class="text-left"><?php echo $customer['source']; ?></td>
                  <td class="text-left"><?php echo $customer['ip']; ?></td>
                  <td class="text-left"><?php echo $customer['date_added']; ?></td>
                  <td class="text-left"><?php if ($customer['approve']) { ?>
                    <?php if($this->user->hasPermission('modify', 'sale/customer_approve')) { ?>
                    <a href="<?php echo $customer['approve']; ?>" data-toggle="tooltip" title="<?php echo $button_verify; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i></a>
                    <?php } } else { ?>
                    <a href="#" class="customer_verified" data-toggle="tooltip" title="Verified"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg></a>
                    <!--<button type="button" disabled><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></button>-->
                    <?php } ?>
                   
                   <?php if($customer['status_row'] && $customer['approved_row']) { ?>
                   <a target="_blank" data-toggle="tooltip" title="Login" href="index.php?path=sale/customer/login&token=<?php echo $token; ?>&customer_id=<?php echo $customer['customer_id']; ?>&store_id=0">
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                   </a>
                   <?php } else { ?>
                   <a data-toggle="tooltip" title="Account Disabled" href="javascript:void(0)" style="cursor:default">
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                   </a>
                   <?php } ?>
                    
                    <?php if ($customer['unlock']) { ?>
                    <a href="<?php echo $customer['unlock']; ?>" data-toggle="tooltip" title="<?php echo $button_unlock; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg></a>
                    <?php } else { ?>
                    <a href="#" class="unlock_customer" data-toggle="tooltip" title="<?php echo $button_unlock; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg></a>
                    <?php } ?>
                    <a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>
                    <a href="<?php echo $customer['customer_view']; ?>" data-toggle="tooltip" title="View"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#51AB66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript">
$('#button-filter').on('click', function() {
  url = 'index.php?path=sale/customer&token=<?php echo $token; ?>';

   var filter_company = $('input[name=\'filter_company\']').val();

   if (filter_company) {
     url += '&filter_company=' + encodeURIComponent(filter_company);
   }
   
   var filter_account_manager_name = $('input[name=\'filter_account_manager_name\']').val();

   if (filter_account_manager_name) {
     url += '&filter_account_manager_name=' + encodeURIComponent(filter_account_manager_name);
   }
   
  var filter_account_manager_id = $('input[name=\'filter_account_manager_name\']').attr("data-account-manager-id");
  //alert(filter_account_manager_id);
  
  if (filter_account_manager_id) {
    url += '&filter_account_manager_id=' + encodeURIComponent(filter_account_manager_id);
  }
  
    var filter_customer_experience = $('input[name=\'filter_customer_experience\']').val();

   if (filter_customer_experience) {
     url += '&filter_customer_experience=' + encodeURIComponent(filter_customer_experience);
   }
   
  var filter_customer_experience_id = $('input[name=\'filter_customer_experience\']').attr("data-customer-experience-id");
  //alert(filter_customer_experience_id);
  
  if (filter_customer_experience_id) {
    url += '&filter_customer_experience_id=' + encodeURIComponent(filter_customer_experience_id);
  }
  
  var filter_sub_customer_show = 0;
  
  if ($('input[name=\'filter_sub_customer_show[]\']').is(':checked')) {
    filter_sub_customer_show = 1;
    url += '&filter_sub_customer_show=' + encodeURIComponent(filter_sub_customer_show);
  } else {
  url += '&filter_sub_customer_show=' + encodeURIComponent(filter_sub_customer_show);    
  }
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }
  
  var filter_email = $('input[name=\'filter_email\']').val();
  
  if (filter_email) {
    url += '&filter_email=' + encodeURIComponent(filter_email);
  }
  
  var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
  
  if (filter_customer_group_id != '*') {
    url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
  } 
  
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status); 
  }
  
  var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
  
  if (filter_payment_terms != '*') {
    url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms); 
  } 
  
  var filter_customer_price_category = $('select[name=\'filter_customer_price_category\']').val();
  
  if (filter_customer_price_category != '*') {
    url += '&filter_customer_price_category=' + encodeURIComponent(filter_customer_price_category); 
  }
  
  var filter_telephone = $('input[name=\'filter_telephone\']').val();
  
  if (filter_telephone != '*') {
    url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
  } 
  
  var filter_ip = $('input[name=\'filter_ip\']').val();
  
  if (filter_ip) {
    url += '&filter_ip=' + encodeURIComponent(filter_ip);
  }
  
  var filter_parent_customer = $('input[name=\'filter_parent_customer\']').val();
  
  if (filter_parent_customer) {
    url += '&filter_parent_customer=' + encodeURIComponent(filter_parent_customer);
  }
  
  var filter_parent_customer_id = $('input[name=\'filter_parent_customer\']').attr("data-parent-customer-id");
  //alert(filter_parent_customer_id);
  
  if (filter_parent_customer_id) {
    url += '&filter_parent_customer_id=' + encodeURIComponent(filter_parent_customer_id);
  }
    
  var filter_date_added = $('input[name=\'filter_date_added\']').val();
  
  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }


   var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();
  
  if (filter_date_added_to) {
    url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
  }
  
  location = url;
});
//--></script> 
  <script type="text/javascript"><!--

  $companyName="";
$('input[name=\'filter_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=sale/customer/autocompletebyCompany&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request)+'&filter_company=' +$companyName,
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_name\']').val(item['label']);
  } 
});

$('input[name=\'filter_parent_customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=sale/customer/autocompleteparentcustomer&token=<?php echo $token; ?>&filter_parent_customer=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_parent_customer\']').val(item['label']);
    $('input[name=\'filter_parent_customer\']').attr("data-parent-customer-id", item['value']);
  } 
});

 $('input[name=\'filter_company\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/customer/autocompletecompany&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['name']
                            }
                        }));

                        
                    }
                });
                $companyName="";
            },
            'select': function (item) {
                $('input[name=\'filter_company\']').val(item['label']);
                $('input[name=\'filter_customer\']').val('');
                $companyName=item['label'];
            }
        });
 
 $('input[name=\'filter_account_manager_name\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=sale/accountmanager/autocompleteaccountmanager&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['user_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_account_manager_name\']').val(item['label']);
                $('input[name=\'filter_account_manager_name\']').attr("data-account-manager-id", item['value']);
            }
 });
 
  $('input[name=\'filter_customer_experience\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?path=dropdowns/dropdowns/getcustomerexperienceteam&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['user_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'filter_customer_experience\']').val(item['label']);
                $('input[name=\'filter_customer_experience\']').attr("data-customer-experience-id", item['value']);
            }
 }); 

$('input[name=\'filter_email\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?path=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['email'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_email\']').val(item['label']);
  } 
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false,
     widgetParent: 'body'
});

function excel() {
            
    url = 'index.php?path=sale/customer/export_excel&token=<?php echo $token; ?>';
    



    
   var filter_company = $('input[name=\'filter_company\']').val();

   if (filter_company) {
     url += '&filter_company=' + encodeURIComponent(filter_company);
   }
   
   var filter_account_manager_name = $('input[name=\'filter_account_manager_name\']').val();

   if (filter_account_manager_name) {
     url += '&filter_account_manager_name=' + encodeURIComponent(filter_account_manager_name);
   }
   
  var filter_account_manager_id = $('input[name=\'filter_account_manager_name\']').attr("data-account-manager-id");
  //alert(filter_account_manager_id);
  
  if (filter_account_manager_id) {
    url += '&filter_account_manager_id=' + encodeURIComponent(filter_account_manager_id);
  }
  
  var filter_customer_experience = $('input[name=\'filter_customer_experience\']').val();

   if (filter_customer_experience) {
     url += '&filter_customer_experience=' + encodeURIComponent(filter_customer_experience);
   }
   
  var filter_customer_experience_id = $('input[name=\'filter_customer_experience\']').attr("data-customer-experience-id");
  //alert(filter_customer_experience_id);
  
  if (filter_customer_experience_id) {
    url += '&filter_customer_experience_id=' + encodeURIComponent(filter_customer_experience_id);
  }
  
  var filter_sub_customer_show = 0;
  
  if ($('input[name=\'filter_sub_customer_show[]\']').is(':checked')) {
    filter_sub_customer_show = 1;
    url += '&filter_sub_customer_show=' + encodeURIComponent(filter_sub_customer_show);
  } else {
  url += '&filter_sub_customer_show=' + encodeURIComponent(filter_sub_customer_show);    
  }
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }
  
  var filter_email = $('input[name=\'filter_email\']').val();
  
  if (filter_email) {
    url += '&filter_email=' + encodeURIComponent(filter_email);
  }
  
  var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
  
  if (filter_customer_group_id != '*') {
    url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
  } 
  
  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status); 
  } 
  
  var filter_payment_terms = $('select[name=\'filter_payment_terms\']').val();
  
  if (filter_payment_terms != '*') {
    url += '&filter_payment_terms=' + encodeURIComponent(filter_payment_terms); 
  }
  
  var filter_customer_price_category = $('select[name=\'filter_customer_price_category\']').val();
  
  if (filter_customer_price_category != '*') {
    url += '&filter_customer_price_category=' + encodeURIComponent(filter_customer_price_category); 
  }
  
  var filter_telephone = $('input[name=\'filter_telephone\']').val();
  
  if (filter_telephone != '*') {
    url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
  } 
  
  var filter_ip = $('input[name=\'filter_ip\']').val();
  
  if (filter_ip) {
    url += '&filter_ip=' + encodeURIComponent(filter_ip);
  }
  
  var filter_parent_customer = $('input[name=\'filter_parent_customer\']').val();
  
  if (filter_parent_customer) {
    url += '&filter_parent_customer=' + encodeURIComponent(filter_parent_customer);
  }
  
  var filter_parent_customer_id = $('input[name=\'filter_parent_customer\']').attr("data-parent-customer-id");
  //alert(filter_parent_customer_id);
  
  if (filter_parent_customer_id) {
    url += '&filter_parent_customer_id=' + encodeURIComponent(filter_parent_customer_id);
  }
    
  var filter_date_added = $('input[name=\'filter_date_added\']').val();
  
  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }


   var filter_date_added_to = $('input[name=\'filter_date_added_to\']').val();
  
  if (filter_date_added_to) {
    url += '&filter_date_added_to=' + encodeURIComponent(filter_date_added_to);
  }
  

  
    location = url;
}
$('a.customer_verified').bind("click", function (e) {
e.preventDefault();
});
$('a.unlock_customer').bind("click", function (e) {
e.preventDefault();
});
//--></script></div>
<?php echo $footer; ?> 

<style>
body {
    position: relative;
}
</style>

