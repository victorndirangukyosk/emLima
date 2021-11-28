<ul id="menu">
    <li id="dashboard"><a href="<?php echo $dashboard; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
    <?php
    if($preturn_general_products != false || $preturn_category != false || $preturn_product != false || $preturn_review != false || $preturn_information != false ) {
    ?>
    <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
        <ul class="collapse">

            <?php if($this->user->isVendor()) { ?>	
            <li><a href="<?php echo $general_products; ?>">General products</a></li>
            <?php } ?>

            <?php if($preturn_product) { ?>	
            <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php 
    if( $preturn_order != false || $preturn_return != false ) {
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
        </ul>
    </li>
    <?php } ?>    

    <?php
    if($preturn_store != false) {
    ?>
    <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_store != false) { ?>
            <li><a href="<?php echo $store; ?>"><?php echo $text_store; ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <?php } ?>

    <?php
    if( $preturn_report_vendor != false || $preturn_report_vendor_order != false || $preturn_sale_order != false || $preturn_sale_tax != false || $preturn_sale_shipping != false || $preturn_sale_return != false || $preturn_sale_coupon != false || $preturn_product_viewed != false || $preturn_product_purchased != false || $preturn_customer_online != false || $preturn_customer_activity != false || $preturn_customer_order != false || $preturn_customer_reward != false || $preturn_customer_credit != false || $preturn_marketing != false  ) {
    ?>
    <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
        <ul class="collapse">
            
            <?php if( $preturn_sale_order != false || $preturn_sale_tax != false || $preturn_sale_shipping != false || $preturn_sale_return != false || $preturn_sale_coupon != false || $preturn_sale_advanced != false ) { ?>
            <li><a class="parent"><?php echo $text_sale; ?></a>
                <ul>
                    <?php if($preturn_sale_order) { ?>
                    <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
                    <?php }?>
                    
                    <?php if($preturn_sale_advanced) { ?>
                    <li><a href="<?php echo $report_sale_advanced; ?>"><?php echo $text_report_sale_advanced; ?></a></li>
                    <?php }?>

                    <?php if($preturn_sale_return) { ?>
                    <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
                    <?php } ?>
                    <?php if($preturn_report_vendor_com) { ?>
                    <li><a href="<?php echo $report_commission; ?>"> Commission</a></li>
                    <?php } ?>
                   
                </ul>
            </li>
            <?php } ?>

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

                    
                    <!-- <?php if($preturn_report_vendor != false){ ?>
                    <li><a href="<?= $report_vendor ?>">Vendor</a></li>
                    <?php } ?>
                    <?php if($preturn_report_vendor_order != false){ ?>
                    <li><a href="<?= $report_vendor_order ?>">Vendor order</a></li>
                    <?php } ?> -->
                    <?php if($preturn_report_store_sales != false){ ?>
                    <li><a href="<?= $report_store_sales ?>">Store Sales</a></li>
                    <?php } ?>
                </ul>
            </li>               
            <?php } ?>
            
            
        <?php if( $preturn_product_viewed != false || $preturn_product_purchased != false ) { ?>
          <li><a class="parent"><?php echo $text_product; ?></a>
          <ul>
         
          <?php if($preturn_product_purchased) { ?>
          <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
          <?php } ?>
        </ul>
    </li>
    <?php } ?>
    
    
</ul>
</li>
    <?php } ?>
    
    <?php
    if( $preturn_export_import != false || $preturn_error_log != false || $preturn_file_manager != false || $preturn_upload != false ) {
    ?>
    <li id="system">
        <a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
        <ul class="collapse">
            <?php if($preturn_export_import) { ?>
            <li><a href="<?php echo $export_import; ?>"><?php echo $text_export_import; ?></a></li>
            <?php } ?>
            
            
        </ul>
    </li>
    <?php } ?>
    <li id="">
        <a class="parent"><i class="fa fa-info-circle"></i> <span><?php echo $text_vendor; ?></span></a>
        <ul class="collapse">
            <li><a href="<?php echo $link_vendor_info; ?>"><?php echo $text_vendor_info; ?></a></li>
        </ul>
    </li>

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
            <!-- <li>
                <a href="<?= $acc_settings ?>">Settings</a>
            </li> -->
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
    <li id="menu-collapse"><a href="#" onclick="return false;" id="button-menu"><i class="fa fa-play-circle rotate-collapse"></i> <span><?php echo $text_collapse; ?></span></a></li>
</ul>
