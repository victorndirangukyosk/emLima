<ul id="menu">
    <?php if(!$this->user->isFarmer() && $this->user->getGroupId() != 21) { ?>
    <li id="dashboard"><a href="<?php echo $dashboard; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
    <?php } ?>
    <?php
    if($preturn_cat_packages != false || $preturn_general_products != false || $preturn_category != false || $preturn_product != false || $preturn_review != false || $preturn_information != false ) {
    ?>
    <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
        <ul class="collapse">


            <!-- <?php if($preturn_cat_packages) { ?>
            <li><a href="<?php echo $cat_packages; ?>">Packages</a></li>
            <?php } ?> -->

            <?php if($preturn_category) { ?>
            <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
            <?php } ?>

            <?php if($preturn_product_collection) { ?>
            <li><a href="<?php echo $product_collection; ?>">Product Collection</a></li>
            <?php } ?>

            <?php if($preturn_checkout_question) { ?>
            <li><a href="<?php echo $checkout_question; ?>">Checkout Questions</a></li>
            <?php } ?>


            <?php if($preturn_recipe_category) { ?>
            <li><a href="<?php echo $recipe_category; ?>">Recipe category</a></li>
            <?php } ?>

            <?php if($preturn_recipe) { ?>
            <li><a href="<?php echo $recipe; ?>">Recipes</a></li>
            <?php } ?>

            <?php if($preturn_help_category) { ?>
            <li><a href="<?php echo $help_category; ?>">Help category</a></li>
            <?php } ?>

            <?php if($preturn_help) { ?>
            <li><a href="<?php echo $help; ?>">Help</a></li>
            <?php } ?>

            <?php if($preturn_general_products) { ?>    
            <li><a href="<?php echo $general_products; ?>">General products</a></li>
            <?php } ?>



            <?php if($preturn_vendor_product) { ?>    
            <li><a href="<?php echo $vendor_product; ?>"><?php echo $text_product; ?></a></li>
            <?php } ?>

            <?php if($preturn_information) { ?>     
            <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
            <?php } ?>  

        </ul>
    </li>
    <?php } ?>

    <?php if($preturn_vendor_product) { ?>    
    <li id="simple-blog">
        <a class="parent"><i class="fa fa-cubes fa-fw"></i> <span>Inventory</span></a>
        <ul>
          <?php if($preturn_vendor_product != false){ ?>
            <li><a class="parent">Inventory</a>
            <ul>
            <li><a href="<?php echo $inventory_management; ?>">Inventory</a></li>
            <li><a href="<?php echo $inventory_management_update_history; ?>">Inventory History</a></li>
            </ul>
          <?php } ?>
          <?php if($preturn_vendor_product != falses){ ?>
          <li><a class="parent">Inven. Management</a>
            <ul>
            <li><a href="<?php echo $inventory_management_update; ?>">Inven. Management</a></li>
            <li><a href="<?php echo $inventory_management_price; ?>">History</a></li>
            </ul>
          <?php } ?>
        </ul>
    </li>
    <?php } ?>
    <?php if($preturn_vendor_product) { ?>  
    <li id="simple-blog">
        <a class="parent"><i class="fa fa-tags fa-fw"></i> <span>Category Prices</span></a>
        <ul>
            <li><a href="<?php echo $category_prices; ?>"><span>Category Prices</span></a></li>
            <li><a href="<?php echo $export_import; ?>"><span><?php echo $text_export_import; ?></span></a></li>
        </ul>
    </li>
    <?php } ?>
    <?php
    if($preturn_simple_blog_author != false || $preturn_simple_blog_category != false || $preturn_simple_blog_article != false || 
    $preturn_simple_blog_comment != false || $preturn_simple_blog_general_setting || $preturn_simple_blog_category_setting != false || $preturn_simple_blog_view_report != false){ 
    ?>
    <li id="simple-blog">
        <a class="parent"><i class="fa fa-file-text-o fa-fw"></i> <span><?php echo $text_simple_blogs; ?></span></a>
        <ul>
            <?php if($preturn_simple_blog_article != false){ ?>
            <li><a href="<?php echo $simple_blog_article; ?>"><?php echo $text_simple_blog_article; ?></a></li>
            <?php } ?>

            <?php if($preturn_simple_blog_category != false){ ?>
            <li><a href="<?php echo $simple_blog_category; ?>"><?php echo $text_simple_blog_category; ?></a></li>
            <?php } ?>

            <?php if($preturn_simple_blog_author != false){ ?>
            <li><a href="<?php echo $simple_blog_author; ?>"><?php echo $text_simple_blog_author; ?></a></li>
            <?php } ?>

            <?php if($preturn_simple_blog_comment != false){ ?>
            <li><a href="<?php echo $simple_blog_comment; ?>"><?php echo $text_simple_blog_article_comment; ?></a></li>
            <?php } ?>

            <?php if($preturn_simple_blog_view_report != false){ ?>
            <li><a href="<?php echo $simple_blog_view_report; ?>"><?php echo $text_simple_blog_view_report; ?></a></li>
            <?php } ?>

            <?php if($preturn_simple_blog_general_setting != false){ ?>
            <li><a class="parent"><?php echo $text_simple_blog_setting; ?></a>
                <ul>
                    <?php if($preturn_simple_blog_general_setting != false){ ?>
                    <li><a href="<?php echo $simple_blog_general_setting; ?>"><?php echo $text_simple_blog_general_setting; ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php 
    if( $preturn_order != false || $preturn_order_delivaries != false || $preturn_order_recurring != false || $preturn_return != false || $preturn_customer != false || $preturn_customer_group != false || $preturn_customer_ban_ip != false || $preturn_paypal != false ) {
    ?>
    <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_order) { ?>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <?php } ?>
            
            <?php if($preturn_fast_order) { ?>
            <li><a href="<?php echo $fast_order; ?>"><?php echo $text_fast_order; ?></a></li>
            <?php } ?>

            <?php if($preturn_return) { ?> 
            <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
            <?php } ?>
            <?php if($preturn_transaction) { ?>  
            <li><a href="<?php echo $transaction; ?>">Order Transactions</a></li>
            <?php } ?>

            
            
            <?php if($preturn_order_product_missing_products) { ?>  
            <li><a href="<?php echo $order_product_missing_products; ?>">Missing Order Products</a></li>
            <?php } ?>
            
            <?php if($preturn_order_delivaries) { ?>
            <li><a href="<?php echo $order_delivaries; ?>">Order Delivaries</a></li>
            <?php } ?>

            <?php if($preturn_order_receivables) { ?>
            <li><a href="<?php echo $order_receivables; ?>">Payment Receivables</a></li>
            <?php } ?>


        </ul>
    </li>
    <?php } ?>

    <?php
    if( $preturn_customer != false || $preturn_customer_group != false || $preturn_customer_ban_ip != false) {
    ?>
    <li><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_customer) { ?>
            <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
            <?php } ?>
            <?php if($preturn_customer_group) { ?>
            <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
            <?php } ?>
            <?php if($preturn_customer_ban_ip) { ?>
            <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
            <?php } ?>


             <?php if($preturn_customer_feedback) { ?>
            <li><a href="<?php echo $customer_feedback; ?>"><?php echo $text_customer_feedback; ?></a></li>
            <?php } ?>
            
            <?php if($preturn_customer) { ?>
            <li><a href="<?php echo $customer_otp; ?>">Customer OTP</a></li>
            <?php } ?>


             <?php if($preturn_customer_issue) { ?>
            <li><a href="<?php echo $customer_issue; ?>"><?php echo $text_customer_issue; ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    
        <?php
    if( $preturn_farmer != false || $preturn_farmertransactions != false) {
    ?>
    <li><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_farmer; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_customer) { ?>
            <li><a href="<?php echo $farmer; ?>"><?php echo $text_farmer; ?></a></li>
            <?php } ?>
            <?php if($preturn_farmertransactions) { ?>
            <li><a href="<?php echo $farmertransactions; ?>"><?php echo $text_farmertransactions; ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php
    if( $preturn_order_dashboard != false || $preturn_account_manager != false) {
    ?>
    <li><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_account_managers; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_order_dashboard) { ?>
            <li><a href="<?php echo $order_dashboard; ?>"><?php echo $text_order_dashboard; ?></a></li>
            <?php } ?>
            
            <?php if($preturn_account_manager) { ?>
            <li><a href="<?php echo $accountmanager; ?>"><?php echo $text_account_managers; ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    
    <?php
    if( $preturn_customer_experience != false) {
    ?>
    <li><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer_experience; ?></span></a>
        <ul class="collapse">
            
            <?php if($preturn_customer_experience) { ?>
            <li><a href="<?php echo $customerexperience; ?>"><?php echo $text_customer_experience; ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php
    if($preturn_account_manager_customers != false) {
    ?>
    <li><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_account_managers_customers; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_account_manager_customers) { ?>
            <li><a href="<?php echo $accountmanager_customers; ?>"><?php echo $text_account_managers_customers; ?></a></li>
            <?php } ?>


              <?php if($preturn_customer_feedback) { ?>
            <li><a href="<?php echo $customer_feedback; ?>"><?php echo $text_customer_feedback; ?></a></li>
            <?php } ?>

            <?php if($preturn_customer_otp) { ?>
            <li><a href="<?php echo $customer_otp; ?>">Customer OTP</a></li>
             <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php if($preturn_account_manager_customer_orders != false){ ?>
    <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_account_manager_customer_orders) { ?>
            <li><a href="<?php echo $account_manager_customer_orders; ?>"><?php echo $text_order; ?></a></li>
            <?php } ?>
        </ul>
    </li>               
    <?php } ?>

    <?php if( $preturn_vendor != false || $preturn_vendor_permission != false) { ?>
    <li>
        <a class="parent">
            <i class="fa fa-user fa-fw"></i> <span>Vendors</span>
        </a>
        <ul>
            <?php if($preturn_vendor) { ?>
            <li><a href="<?php echo $vendor; ?>">Vendor</a></li>
            <?php } ?>
            <?php if($preturn_vendor_permission) { ?>
            <li><a href="<?php echo $vendor_group; ?>">Vendor group</a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php if($preturn_store != false) { ?>
    <li>
        <a class="parent">
            <i class="fa fa-university fa-fw"></i> <span><?php echo $text_store; ?></span>
        </a>
        <ul>
            <?php if($preturn_store != false) { ?>
            <li>
                <a href="<?php echo $store; ?>">
                    <?php echo $text_store; ?>
                </a>
            </li>
            <?php } ?>


            <?php if($preturn_store_group != false) { ?>
            <li>
                <a href="<?php echo $store_group; ?>">
                    <?php echo $text_store_group; ?>
                </a>
            </li>
            <?php } ?>

            <?php if($preturn_store_type != false) { ?>
            <li>
                <a href="<?php echo $store_type; ?>">
                    <?php echo $text_store_type; ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>



    <?php if( $preturn_shopper != false || $preturn_shopper_permission != false) { ?>
    <!-- <li>
        <a class="parent">
            <i class="fa fa-user fa-fw"></i> <span>Delivery Boy</span>
        </a>
        <ul>
            <?php if($preturn_shopper) { ?>
            <li><a href="<?php echo $shopper; ?>">Delivery Boy</a></li>
            <?php } ?>
            <?php if($preturn_shopper_permission) { ?>
            <li><a href="<?php echo $shopper_group; ?>">Delivery Boy Groups</a></li>
            <?php } ?>
        </ul>
    </li> -->
    <?php } ?>

    <?php
    if( $preturn_approvals_product != false || $preturn_shopper != false || $preturn_enquiries != false) {
    ?>
    <li>
        <a class="parent">
            <i class="fa fa-check fa-fw"></i>
            <span>Approvals</span>
        </a>
        <ul class="collapse">
            <?php if($preturn_shopper != false){ ?>
            <li><a href="<?php echo $approve_shopper; ?>">Approve Delivery Boy</a></li>
            <?php } ?>
            <?php if($preturn_enquiries != false){ ?>
            <li><a href="<?php echo $approve_vendors; ?>">Approve Vendors</a></li>
            <?php } ?>
            <?php if($preturn_approvals_product != false){ ?>
            <li><a href="<?php echo $approve_products; ?>">Approve Products</a></li>        
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php
    if( $preturn_marketing != false || $preturn_coupon != false || $preturn_contact != false || $preturn_offer != false ) {
    ?>
    <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_coupon) { ?>  
            <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
            <?php } ?>

            <?php if($preturn_offer) { ?>  
            <li><a href="<?php echo $offer; ?>"><?php echo $text_offer; ?></a></li>
            <?php } ?>

            <?php if($preturn_contact) { ?> 
            <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
            <?php } ?> 
        </ul>
    </li>
    <?php } ?>

    <!-- <li><a class="parent"><i class="fa fa-envelope-o fa-fw"></i> <span>Bulk Email</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $bulk_email; ?>">Send Bulk Email</a></li>           
            <li><a href="<?php echo $email_groups; ?>">Email Groups</a></li>
        </ul>
    </li> -->
    <?php
    if( $preturn_account_manager_sale_order != false || $preturn_account_manager_customer_order != false) {
    ?>
    <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
        <ul class="collapse">
            <?php if( $preturn_account_manager_sale_order != false || $preturn_account_manager_customer_order != false || $preturn_account_manager_customer_activity != false || $preturn_account_manager_customer_online != false ) { ?>
            <li><a class="parent"><?php echo $text_sale; ?></a>
                <ul>
                    <?php if($preturn_account_manager_sale_order) { ?>
                    <li><a href="<?php echo $report_account_manager_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
                    <?php }?>
                </ul>
            </li>
            <?php } ?>
            <?php if( $preturn_account_manager_customer_order != false || $preturn_account_manager_customer_activity != false || $preturn_account_manager_customer_online != false) { ?>
            <li><a class="parent"><?php echo $text_customer; ?></a>
                <ul>
                    <?php if($preturn_account_manager_customer_activity) { ?>
                    <li><a href="<?php echo $report_account_manager_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_account_manager_customer_online) { ?>
                    <li><a href="<?php echo $report_account_manager_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_account_manager_customer_order) { ?>
                    <li><a href="<?php echo $report_account_manager_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    
    <?php
    if( $preturn_report_shopper_order!= false || $preturn_report_shopper!= false || $preturn_report_vendor != false || $preturn_report_vendor_order != false || $preturn_sale_order != false || $preturn_sale_advanced != false  || $preturn_sale_tax != false || $preturn_sale_payment != false || $preturn_sale_transaction != false  || $preturn_sale_shipping != false || $preturn_sale_return != false || $preturn_sale_coupon != false || $preturn_product_viewed != false || $preturn_product_purchased != false || $preturn_customer_online != false || $preturn_customer_activity != false || $preturn_customer_order != false || $preturn_customer_reward != false || $preturn_customer_credit != false || $preturn_marketing != false  ) {
    ?>
    <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
        <ul class="collapse">

            <?php if($preturn_report_shopper != false){ ?>
            <!-- <li><a href="<?= $report_shopper ?>">Delivery Boy</a></li> -->
            <?php } ?>

            <?php if($preturn_report_shopper_order != false){ ?>
            <!-- <li><a href="<?= $report_shopper_order ?>">Delivery Boy order</a></li> -->
            <?php } ?>

            <!-- <?php if($preturn_report_income != false){ ?>
            <li><a href="<?= $report_income ?>">Income</a></li>
            <?php } ?> -->

            <?php if($preturn_report_vendor != false || $preturn_report_vendor_order != false  || $preturn_report_store_sales != false){ ?>
            <li><a class="parent">Vendor</a>
                <ul>

                    <?php if($preturn_report_vendor_orders != false){ ?>
                    <li><a href="<?= $report_vendor_orders ?>">Orders</a></li>
                    <?php } ?>
                    <?php if($preturn_report_vendor_returns != false){ ?>
                    <li><a href="<?= $report_vendor_returns ?>">Returns</a></li>
                    <?php } ?>

                    <?php if($preturn_report_combined_report != false){ ?>
                    <li><a href="<?= $report_combined_report ?>">Combined Report</a></li>
                    <?php } ?>


                    <?php if($preturn_report_vendor != false){ ?>
                    <li><a href="<?= $report_vendor ?>">Vendor</a></li>
                    <?php } ?>
                    <?php if($preturn_report_vendor_order != false){ ?>
                    <li><a href="<?= $report_vendor_order ?>">Vendor order</a></li>
                    <?php } ?>
                    <?php if($preturn_report_store_sales != false){ ?>
                    <li><a href="<?= $report_store_sales ?>">Store Sales</a></li>
                    <?php } ?>
                </ul>
            </li>               
            <?php } ?>
            <?php if( $preturn_sale_order != false || $preturn_sale_tax != false || $preturn_sale_transaction != false || $preturn_sale_payment != false  || $preturn_sale_shipping != false || $preturn_sale_return != false || $preturn_sale_coupon != false || $preturn_sale_advanced != false ) { ?>
            <li><a class="parent"><?php echo $text_sale; ?></a>
                <ul>
                    <?php if($preturn_sale_order) { ?>
                    <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
                    <?php }?>

                    <?php if($preturn_sale_advanced) { ?>
                    <li><a href="<?php echo $report_sale_advanced; ?>"><?php echo $text_report_sale_advanced; ?></a></li>
                    <?php }?>

                    <?php if($preturn_sale_productmissing) { ?>
                    <li><a href="<?php echo $report_sale_productmissing; ?>"><?php echo $text_report_sale_productmissing; ?></a></li>
                    <?php }?>


                    <?php if($preturn_sale_tax) { ?>
                    <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_sale_shipping) { ?>
                    <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
                    <?php }?>

                    <?php if($preturn_sale_payment) { ?>
                    <li><a href="<?php echo $report_sale_payment; ?>"><?php echo $text_report_sale_payment; ?></a></li>
                    <?php }?>

                    <?php if($preturn_sale_transaction) { ?>
                    <li><a href="<?php echo $report_sale_transaction; ?>"><?php echo $text_report_sale_transaction; ?></a></li>
                    <?php }?>


                    <?php if($preturn_sale_return) { ?>
                    <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_sale_coupon) { ?>
                    <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_vendor_commission) { ?>
                    <li><a href="<?php echo $report_vendor_commission; ?>"><?php echo 'Commission'; ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php } ?>

            <?php if( $preturn_product_viewed != false || $preturn_product_purchased != false ) { ?>
            <li><a class="parent"><?php echo $text_product; ?></a>
                <ul>
                    <?php if($preturn_product_viewed) { ?>
                    <!-- <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li> -->
                    <?php } ?>
                    <?php if($preturn_product_purchased) { ?>
                    <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
                    <?php } ?>

                     <?php if($preturn_inventory_daily_prices) { ?>
                    <li><a href="<?php echo $report_inventory_daily_prices; ?>"><?php echo $text_report_inventory_daily_prices; ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php } ?>
            <?php if( $preturn_customer_online != false || $preturn_customer_activity != false || $preturn_customer_order != false || $preturn_customer_reward != false || $preturn_customer_credit != false ) { ?>
            <li><a class="parent"><?php echo $text_customer; ?></a>
                <ul>

                    <?php if($preturn_customer_activity) { ?>
                    <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_customer_online) { ?>
                    <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_customer_order) { ?>
                    <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_customer_reward) { ?>
                    <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_customer_credit) { ?>
                    <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_customer_order) { ?>
                    <li><a href="<?php echo $report_customer_statement; ?>"><?php echo $text_report_customer_statement; ?></a></li>
                    <?php } ?>

                    <?php if($preturn_customer_order_pattern) { ?>
                    <li><a href="<?php echo $report_customer_order_pattern; ?>"><?php echo $text_report_customer_order_pattern; ?></a></li>
                    <?php } ?>

                     <?php if($preturn_customer_order_count) { ?>
                    <li><a href="<?php echo $report_customer_order_count; ?>"><?php echo $text_report_customer_order_count; ?></a></li>
                    <?php } ?>


                     <?php if($preturn_customer_order) { ?>
                    <li><a href="<?php echo $report_customer_boughtproducts; ?>"><?php echo $text_report_customer_boughtproducts; ?></a></li>
                    <?php } ?>


                    
                     <?php if($preturn_customer_orderplaced) { ?>
                    <li><a href="<?php echo $report_customer_orderplaced; ?>"><?php echo $text_report_customer_orderplaced; ?></a></li>
                    <?php } ?>

                     <?php if($preturn_customer_onboarded) { ?>
                    <li><a href="<?php echo $report_customer_onboarded; ?>"><?php echo $text_report_customer_onboarded; ?></a></li>
                    <?php } ?>

                     <?php if($preturn_customer_unordered) { ?>
                    <li><a href="<?php echo $report_customer_unordered; ?>"><?php echo $text_report_customer_unordered; ?></a></li>
                    <?php } ?>


                       <?php if($preturn_customer_wallet) { ?>
                    <li><a href="<?php echo $report_customer_wallet; ?>"><?php echo $text_report_customer_wallet; ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php }?>
            
            <?php if( $preturn_farmer_activity != false) { ?>
            <li><a class="parent"><?php echo $text_farmer; ?></a>
                <ul>
                    <?php if($preturn_farmer_activity) { ?>
                    <li><a href="<?php echo $report_farmer_activity; ?>"><?php echo $text_report_farmer_activity; ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            
            <?php if( $preturn_user_activity != false ) { ?>
            <li><a class="parent"><?php echo $text_user; ?></a>
                <ul>

                    <?php if($preturn_user_activity) { ?>
                    <li><a href="<?php echo $report_user_activity; ?>"><?php echo $text_report_user_activity; ?></a></li>
                    <?php } ?>

                </ul>
            </li>
            <?php }?>

        </ul>
    </li>
    <?php } ?>

    <?php
    if( $preturn_design_offer != false || $preturn_design_notice != false) {
    ?>
    <li id="appearance"><a class="parent"><i class="fa fa-desktop fa-fw"></i> <span><?php echo $text_appearance; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_design_slider) { ?>
            <li><a href="<?php echo $design_slider; ?>">Sliders</a></li>
            <?php } ?>

            <?php if($preturn_design_offer) { ?>
            <li><a href="<?php echo $design_offer; ?>">Offers</a></li>
            <?php } ?>
            <?php if($preturn_design_notice) { ?>
            <li><a href="<?php echo $notice; ?>">Notice</a></li>
            <?php } ?>
            <?php if($preturn_design_blocks) { ?>
            <li><a href="<?php echo $blocks; ?>">Blocks</a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    <?php if($preturn_marketplace || $preturn_payment != false || $preturn_shipping != false || $preturn_total != false || $preturn_feed != false) { ?>
    <li id="marketplace">
        <a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_marketplace; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_payment) { ?>
            <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
            <?php } ?>
            <?php if($preturn_shipping) { ?>
            <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
            <?php } ?>
            <?php if($preturn_total) { ?>
            <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
            <?php } ?>
            <!--
            <?php if($preturn_feed) { ?>
            <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
            <?php } ?>
            -->
        </ul>
    </li>
    <?php } ?>
    <?php
    if( $preturn_localisation != false || $preturn_language != false || $preturn_currency != false || $preturn_order_status != false || $preturn_return_status != false || $preturn_return_action != false || $preturn_return_reason != false || $preturn_tax_rate != false || $preturn_tax_class != false || $preturn_city != false || $preturn_stock_status != false ) { ?>
    <li><a class="parent"><i class="fa fa-flag fa-fw"></i> <span><?php echo $text_localisation; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_localisation) { ?>
            <li><a href="<?php echo $localisation; ?>"><?php echo $text_localisation; ?></a></li>
            <?php  } ?>
            <?php if($preturn_language) { ?>
            <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
            <?php } ?>
            <?php if($preturn_currency) { ?>
            <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
            <?php } ?>
            <?php if($preturn_stock_status) { ?>
            <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
            <?php } ?>
            <?php if($preturn_order_status) { ?>
            <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
            <?php } ?>

            <?php if($preturn_app_order_status) { ?>
            <li><a href="<?php echo $app_order_status; ?>"><?php echo $text_app_order_status; ?></a></li>
            <?php } ?>

            <?php if($preturn_app_order_status_mapping) { ?>
            <li><a href="<?php echo $app_order_status_mapping; ?>"><?php echo $text_app_order_status_mapping; ?></a></li>
            <?php } ?>


            <?php if($preturn_delivery_statuses && $enabled_delivery_statuses) { ?>
            <li><a href="<?php echo $delivery_statuses; ?>"><?php echo $text_delivery_statuses; ?></a></li>
            <?php } ?>

            <?php if( $preturn_return_status != false || $preturn_return_action != false || $preturn_return_reason != false ) { ?>
            <li><a class="parent"><?php echo $text_return; ?></a>
                <ul>
                    <?php if($preturn_return_status) { ?>
                    <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_return_action) { ?>
                    <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_return_reason) { ?>
                    <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
                    <?php }?>
                </ul>
            </li>
            <?php } ?>
            <?php if($preturn_city) { ?>
            <li><a href="<?php echo $city; ?>"><?php echo $text_city; ?></a></li>
            <?php } ?>

            <?php if($preturn_state) { ?>
            <li><a href="<?php echo $state; ?>"><?php echo $text_state; ?></a></li>
            <?php } ?>
            
            <?php if($preturn_region) { ?>
            <li><a href="<?php echo $region; ?>">Region</a></li>
            <?php } ?>

            <?php if( $preturn_tax_rate != false || $preturn_tax_class != false ) { ?>
            <li><a class="parent"><?php echo $text_tax; ?></a>
                <ul>
                    <?php if($preturn_tax_class) { ?>
                    <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_tax_rate) { ?>
                    <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <!-- <?php if($preturn_transactions_package) { ?>   
    <li id="translations">
        <a class="parent"><i class="fa fa-money fa-fw"></i> <span>Transactions</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $transactions_package; ?>">Package</a></li>
        </ul>
    </li>
    <?php } ?> -->

    <?php if($preturn_customer_wallet || $preturn_vendor_wallet != false || $preturn_admin_wallet != false) { ?>
    <li id="marketplace">
        <a class="parent"><i class="fa fa-money fa-fw"></i> <span>Wallets</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $customer_wallet; ?>">Customer Wallet</a></li>
            <li><a href="<?php echo $vendor_wallet; ?>">Vendor Wallet</a></li>
            <li><a href="<?php echo $admin_wallet; ?>">Admin Wallet</a></li>
        </ul>
    </li>
    <?php } ?>


    <?php
    if($preturn_testimonial != false || $preturn_setting != false || $preturn_shopper != false || $preturn_shopper_permission != false || $preturn_user != false || $preturn_user_permission != false || $preturn_user_api != false || $preturn_email_template != false || $preturn_jobposition != false) {
    ?>
    <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
        <ul class="collapse">


            <?php if( $preturn_setting != false  ) { ?>
            <li><a class="parent">Settings</a>
                <ul>
                    <?php if($preturn_setting) { ?> 
                    <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_setting) { ?>                     
                    <li><a href="<?php echo $setting_email; ?>">Email Settings</a></li>
                    <!--<?php } ?>-->
                </ul>
            </li>
            <?php } ?>


            <?php if($preturn_setting_seo != false) { ?>
            <li><a href="<?php echo $setting_seo; ?>">SEO Setting</a></li>
            <?php } ?>

            <?php if($preturn_testimonial != false) { ?>
            <li><a href="<?php echo $testimonial; ?>">Testimonials</a></li>
            <?php } ?>

            <?php if( $preturn_user != false || $preturn_user_permission != false || $preturn_user_api != false ) { ?>
            <li><a class="parent"><?php echo $text_users; ?></a>
                <ul>
                    <?php if($preturn_user) { ?>
                    <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_user_permission) { ?>
                    <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>


            <?php if($preturn_email_template) { ?>
            <li><a href="<?php echo $email_template; ?>"><?php echo $text_email_template; ?></a></li>
            <?php } ?>


            <?php if($preturn_newfeature != false) { ?>
            <li><a href="<?php echo $newfeature; ?>">New Feature Request</a></li>
            <?php } ?>

            <?php if($preturn_jobposition != false) { ?>
            <li><a href="<?php echo $jobposition; ?>">Job Positions</a></li>
            <?php } ?>


        </ul>
    </li>
    <?php } ?>

    <?php
    if( $preturn_export_import != false || $preturn_backup != false || $preturn_error_log != false || $preturn_file_manager != false || $preturn_upload != false ) {
    ?>
    <li id="system">
        <a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_backup) { ?>
            <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
            <?php } ?>
            <?php if($preturn_export_import) { ?>
            <li><a href="<?php echo $export_import; ?>"><?php echo $text_export_import; ?></a></li>
            <?php } ?>
            <?php if($preturn_export_import) { ?>
            <li><a href="<?php echo $clear_data; ?>"><?php echo $text_clear_data; ?></a></li>
            <?php } ?>

            <?php if($preturn_upload) { ?>
            <!-- <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li> -->
            <?php } ?>
            <?php if($preturn_error_log) { ?>
            <!-- <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li> -->
            <?php } ?>
        </ul>
    </li>
    <?php } ?>
    <?php
    if( $preturn_drivers != false) {
    ?>
    <li id="system">
        <a class="parent"><i class="fa fa-truck fa-fw"></i> <span>Drivers</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $drivers_list; ?>">Drivers</a></li>
        </ul>
    </li>
    <?php 
    }
    ?>
    
    <?php
    if( $preturn_vehicles != false) {
    ?>
    <li id="system">
        <a class="parent"><i class="fa fa-truck fa-fw"></i> <span>Vehicles</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $vehicles_list; ?>">Vehicles</a></li>
        </ul>
    </li>
    <?php 
    }
    ?>
    
    <?php
    if( $preturn_executives != false) {
    ?>
    <li id="system">
        <a class="parent"><i class="fa fa-truck fa-fw"></i> <span>Delivery Executives</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $executives_list; ?>">Executives</a></li>
        </ul>
    </li>
    <?php 
    }
    ?>
    
    <?php
    if( $preturn_orderprocessinggroups != false || $preturn_orderprocessor != false) {
    ?>
    <li id="system">
        <a class="parent"><i class="fa fa-truck fa-fw"></i> <span>Order Processing</span></a>
        <ul class="collapse">
            <li><a href="<?php echo $orderprocessinggroup_list; ?>">Groups</a></li>
            <li><a href="<?php echo $orderprocessor_list; ?>">Order Processors</a></li>
        </ul>
    </li>
    <?php 
    }
    ?>
    
    <?php
    if( $this->user->isVendor()){
    if( $preturn_acc_profile != false || $preturn_acc_settings != false || $preturn_acc_packages != false) {
    ?>
    <li id="account">
        <a class="parent"><i class="fa fa-user"></i> <span>My Account</span></a>
        <ul class="collapse">
            <?php if( $preturn_acc_profile != false){ ?>
            <li>
                <a href="<?= $acc_profile ?>">Profile</a>
            </li>
            <?php } ?>
            <?php if($preturn_acc_settings != false){ ?>
            <li>
                <a href="<?= $acc_settings ?>">Settings</a>
            </li>
            <li>
                <a href="<?= $acc_password ?>">Change Password</a>
            </li>
            <?php } ?>
            <!-- <?php if($preturn_acc_packages != false) { ?>
            <li>
                <a href="<?= $acc_packages ?>">Packages</a>
            </li>
            <?php } ?> -->
        </ul>
    </li>
    <?php } ?>      
    <?php } ?>    

    <li id="menu-collapse">
        <a href="#" onclick="return false;" id="button-menu">
            <i class="fa fa-play-circle rotate-collapse"></i>
            <span><?php echo $text_collapse; ?></span>
        </a>
    </li>
</ul>
