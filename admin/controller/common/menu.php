<?php

class ControllerCommonMenu extends Controller {

    public function index() {
        if ($this->user->isVendor()) {
            return $this->vendor_menu();
        } else {
            return $this->admin_menu();
        }
    }

    private function admin_menu() {
        $this->load->language('common/menu');

        $data = $this->language->all();
        $data['text_recurring'] = $this->language->get('text_recurring');

        $data['text_simple_blogs'] = $this->language->get('text_simple_blogs');
        $data['text_simple_blog_author'] = $this->language->get('text_simple_blog_author');
        $data['text_simple_blog_category'] = $this->language->get('text_simple_blog_category');
        $data['text_simple_blog_article'] = $this->language->get('text_simple_blog_article');
        $data['text_simple_blog_article_comment'] = $this->language->get('text_simple_blog_article_comment');
        $data['text_simple_blog_view_report'] = $this->language->get('text_simple_blog_view_report');
        $data['text_simple_blog_setting'] = $this->language->get('text_simple_blog_setting');
        $data['text_simple_blog_general_setting'] = $this->language->get('text_simple_blog_general_setting');
        $data['text_simple_blog_category_setting'] = $this->language->get('text_simple_blog_category_setting');

        $data['simple_blog_author'] = $this->url->link('simple_blog/author', 'token=' . $this->session->data['token'], 'SSL');
        $data['simple_blog_category'] = $this->url->link('simple_blog/category', 'token=' . $this->session->data['token'], 'SSL');
        $data['simple_blog_article'] = $this->url->link('simple_blog/article', 'token=' . $this->session->data['token'], 'SSL');
        $data['simple_blog_comment'] = $this->url->link('simple_blog/comment', 'token=' . $this->session->data['token'], 'SSL');
        $data['simple_blog_general_setting'] = $this->url->link('module/simple_blog', 'token=' . $this->session->data['token'], 'SSL');
        $data['simple_blog_category_setting'] = $this->url->link('module/simple_blog_category', 'token=' . $this->session->data['token'], 'SSL');
        $data['simple_blog_view_report'] = $this->url->link('simple_blog/report', 'token=' . $this->session->data['token'], 'SSL');

        $data['acc_profile'] = $this->url->link('account/profile', 'token=' . $this->session->data['token'], 'SSL');
        $data['acc_settings'] = $this->url->link('account/settings', 'token=' . $this->session->data['token'], 'SSL');
        $data['acc_password'] = $this->url->link('account/settings/password', 'token=' . $this->session->data['token'], 'SSL');
        $data['acc_packages'] = $this->url->link('account/packages', 'token=' . $this->session->data['token'], 'SSL');
        $data['cat_packages'] = $this->url->link('catalog/packages', 'token=' . $this->session->data['token'], 'SSL');

        $data['transactions_package'] = $this->url->link('transactions/package', 'token=' . $this->session->data['token'], 'SSL');

        $data['approve_shopper'] = $this->url->link('approvals/shopper', 'token=' . $this->session->data['token'], 'SSL');
        $data['approve_vendors'] = $this->url->link('approvals/enquiries', 'token=' . $this->session->data['token'], 'SSL');
        $data['approve_products'] = $this->url->link('approvals/product', 'token=' . $this->session->data['token'], 'SSL');
        $data['general_products'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'], 'SSL');
        $data['dashboard'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
        $data['api'] = $this->url->link('user/api', 'token=' . $this->session->data['token'], 'SSL');
        $data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
        $data['design_offer'] = $this->url->link('design/offer', 'token=' . $this->session->data['token'], 'SSL');
        $data['design_slider'] = $this->url->link('design/slider', 'token=' . $this->session->data['token'], 'SSL');

        $data['offer'] = $this->url->link('marketing/offer', 'token=' . $this->session->data['token'], 'SSL');
        /* $data['design_offer'] = $this->url->link('design/offer', 'token=' . $this->session->data['token'], 'SSL'); */
        $data['notice'] = $this->url->link('design/notice', 'token=' . $this->session->data['token'], 'SSL');
        $data['blocks'] = $this->url->link('design/blocks', 'token=' . $this->session->data['token'], 'SSL');

        $data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');

        $data['product_collection'] = $this->url->link('promotion/page', 'token=' . $this->session->data['token'], 'SSL');
        $data['checkout_question'] = $this->url->link('catalog/question', 'token=' . $this->session->data['token'], 'SSL');

        $data['recipe_category'] = $this->url->link('catalog/recipe_category', 'token=' . $this->session->data['token'], 'SSL');
        $data['recipe'] = $this->url->link('catalog/recipe', 'token=' . $this->session->data['token'], 'SSL');

        $data['help_category'] = $this->url->link('catalog/help_category', 'token=' . $this->session->data['token'], 'SSL');
        $data['help'] = $this->url->link('catalog/help', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_income'] = $this->url->link('report/income', 'token=' . $this->session->data['token'], 'SSL');

        $data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL');
        $data['contact'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], 'SSL');
        $data['coupon'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL');
        $data['offer'] = $this->url->link('marketing/offer', 'token=' . $this->session->data['token'], 'SSL');
        $data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL');
        $data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_added&order=DESC', 'SSL');
        $data['customer_otp'] = $this->url->link('sale/customer/customer_otp', 'token=' . $this->session->data['token'] . '&sort=c.date_added&order=DESC', 'SSL');
        $data['farmer'] = $this->url->link('sale/farmer', 'token=' . $this->session->data['token'] . '&sort=c.created_at&order=DESC', 'SSL');
        $data['farmertransactions'] = $this->url->link('sale/farmer_transactions', 'token=' . $this->session->data['token'] . '&sort=c.created_at&order=DESC', 'SSL');

        $data['accountmanager'] = $this->url->link('sale/accountmanager', 'token=' . $this->session->data['token'] . '&sort=c.date_added&order=DESC', 'SSL');
        $data['accountmanager_customers'] = $this->url->link('sale/accountmanageruser', 'token=' . $this->session->data['token'] . '&sort=c.date_added&order=DESC', 'SSL');
        $data['account_manager_customer_orders'] = $this->url->link('sale/accountmanageruserorders', 'token=' . $this->session->data['token'], 'SSL');
        $data['customerexperience'] = $this->url->link('sale/customerexperience', 'token=' . $this->session->data['token'] . '&sort=c.date_added&order=DESC', 'SSL');

        $data['bulk_email'] = $this->url->link('email/bulk_email', 'token=' . $this->session->data['token'], 'SSL');
        $data['email_groups'] = $this->url->link('email/groups', 'token=' . $this->session->data['token'], 'SSL');

        $data['customer_fields'] = $this->url->link('sale/customer_field', 'token=' . $this->session->data['token'], 'SSL');
        $data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
        $data['customer_ban_ip'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], 'SSL');
        $data['customer_feedback'] = $this->url->link('sale/customer_feedback', 'token=' . $this->session->data['token'], 'SSL');
        $data['customer_issue'] = $this->url->link('sale/customer_issue', 'token=' . $this->session->data['token'], 'SSL');
        $data['custom_field'] = $this->url->link('sale/custom_field', 'token=' . $this->session->data['token'], 'SSL');
        $data['email_template'] = $this->url->link('system/email_template', 'token=' . $this->session->data['token'], 'SSL');
        $data['language_override'] = $this->url->link('system/language_override', 'token=' . $this->session->data['token'], 'SSL');
        $data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
        $data['export_import'] = $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL');

        $data['clear_data'] = $this->url->link('tool/clear_data', 'token=' . $this->session->data['token'], 'SSL');
        $data['file_manager'] = $this->url->link('tool/file_manager', 'token=' . $this->session->data['token'], 'SSL');
        $data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
        $data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
        $data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL');
        $data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
        $data['installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], 'SSL');
        $data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL');
        $data['location'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], 'SSL');
        $data['modification'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL');
        $data['marketing'] = $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], 'SSL');
        $data['marketplace'] = $this->url->link('extension/marketplace', 'token=' . $this->session->data['token'], 'SSL');
        $data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_delivaries'] = $this->url->link('sale/amitruckdelivaries', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_product_missing'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_product_missing_products'] = $this->url->link('sale/order_product_missing_products', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_dashboard'] = $this->url->link('sale/orderdashboard', 'token=' . $this->session->data['token'], 'SSL');
        $data['vendor_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        $data['vendor_fast_order'] = $this->url->link('sale/fast_order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1&filter_order_day=today', 'SSL');

        $data['fast_order'] = $this->url->link('sale/fast_order', 'token=' . $this->session->data['token'] . '&filter_order_status=14,1&filter_order_day=today', 'SSL');

        $data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL');
        $data['app_order_status'] = $this->url->link('localisation/app_order_status', 'token=' . $this->session->data['token'], 'SSL');

        $data['app_order_status_mapping'] = $this->url->link('localisation/app_order_status_mapping', 'token=' . $this->session->data['token'], 'SSL');

        $data['delivery_statuses'] = $this->url->link('localisation/delivery_statuses', 'token=' . $this->session->data['token'], 'SSL');

        $data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
        $data['paypal_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL');
        $data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_shopper_order'] = $this->url->link('report/shopper_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_sale_order'] = $this->url->link('report/account_manager_sale_order', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_advanced'] = $this->url->link('report/sale_advanced', 'token=' . $this->session->data['token'] . '&filter_order_status_id=5', 'SSL');

        $data['report_sale_productmissing'] = $this->url->link('report/sale_productmissing', 'token=' . $this->session->data['token'] . '&filter_order_status_id=5', 'SSL');

        $data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_payment'] = $this->url->link('report/sale_payment', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_transaction'] = $this->url->link('report/sale_transaction', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_activity'] = $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_farmer_activity'] = $this->url->link('report/farmer_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_customer_activity'] = $this->url->link('report/account_manager_customer_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_customer_online'] = $this->url->link('report/account_manager_customer_online', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_user_activity'] = $this->url->link('report/user_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_orderplaced'] = $this->url->link('report/customer_orderplaced', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_onboarded'] = $this->url->link('report/customer_onboarded', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_wallet'] = $this->url->link('report/customer_wallet', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_unordered'] = $this->url->link('report/customer_unordered', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_customer_order'] = $this->url->link('report/account_manager_customer_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_shopper'] = $this->url->link('report/shopper', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_statement'] = $this->url->link('report/customer_order/statement', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_boughtproducts'] = $this->url->link('report/customer_boughtproducts', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_order_pattern'] = $this->url->link('report/customer_order_pattern', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_order_count'] = $this->url->link('report/customer_order_count', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_inventory_daily_prices'] = $this->url->link('report/inventory_daily_prices', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_commission'] = $this->url->link('report/commission', 'token=' . $this->session->data['token'], 'SSL');

        $data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
        $data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');
        $data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL');
        $data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL');
        $data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL');
        $data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
        $data['setting'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
        $data['setting_email'] = $this->url->link('setting/setting/setting_email', 'token=' . $this->session->data['token'], 'SSL');

        $data['setting_seo'] = $this->url->link('setting/seo', 'token=' . $this->session->data['token'], 'SSL');

        $data['store'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
        $data['store_group'] = $this->url->link('setting/store_group', 'token=' . $this->session->data['token'], 'SSL');
        $data['store_type'] = $this->url->link('setting/store_type', 'token=' . $this->session->data['token'], 'SSL');
        $data['testimonial'] = $this->url->link('setting/testimonial', 'token=' . $this->session->data['token'], 'SSL');
        $data['newfeature'] = $this->url->link('setting/newfeature', 'token=' . $this->session->data['token'], 'SSL');
        $data['jobposition'] = $this->url->link('setting/jobposition', 'token=' . $this->session->data['token'], 'SSL');
        $data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL');
        $data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL');
        $data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL');
        $data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
        $data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL');
        $data['vendor'] = $this->url->link('vendor/vendor', 'token=' . $this->session->data['token'], 'SSL');
        $data['vendor_group'] = $this->url->link('vendor/vendor_permission', 'token=' . $this->session->data['token'], 'SSL');

        //$data['vendor_subaccount_form'] = $this->url->link('vendor/subaccount_form', 'token=' . $this->session->data['token'], 'SSL');

        $data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
        $data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');

        $data['shopper'] = $this->url->link('shopper/shopper', 'token=' . $this->session->data['token'], 'SSL');
        $data['shopper_group'] = $this->url->link('shopper/shopper_permission', 'token=' . $this->session->data['token'], 'SSL');

        $data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
        $data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
        $data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL');
        $data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL');
        $data['city'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'], 'SSL');
        $data['state'] = $this->url->link('localisation/state', 'token=' . $this->session->data['token'], 'SSL');
        $data['region'] = $this->url->link('localisation/region', 'token=' . $this->session->data['token'], 'SSL');
        $data['recurring'] = $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL');

        //Appearance
        $data['customizer'] = $this->url->link('appearance/customizer', 'token=' . $this->session->data['token'], 'SSL');
        $data['layout'] = $this->url->link('appearance/layout', 'token=' . $this->session->data['token'], 'SSL');
        $data['menu'] = $this->url->link('appearance/menu', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_order'] = $this->url->link('report/vendor_order', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_orders'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_returns'] = $this->url->link('report/vendor_returns', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_combined_report'] = $this->url->link('report/combined_report', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_store_sales'] = $this->url->link('report/store_sales', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_vendor'] = $this->url->link('report/vendor', 'token=' . $this->session->data['token'], 'SSL');

        $data['vendor_product'] = $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'], 'SSL');

        $data['inventory_management'] = $this->url->link('catalog/vendor_product/inventory', 'token=' . $this->session->data['token'], 'SSL');
        $data['inventory_management_update'] = $this->url->link('catalog/vendor_product/Manageinventory', 'token=' . $this->session->data['token'], 'SSL');
        $data['inventory_management_update_history'] = $this->url->link('catalog/vendor_product/InventoryHistory', 'token=' . $this->session->data['token'], 'SSL');
        $data['inventory_management_price'] = $this->url->link('catalog/vendor_product/InventoryPriceHistory', 'token=' . $this->session->data['token'], 'SSL');

        $data['category_prices'] = $this->url->link('catalog/vendor_product/category_priceslist', 'token=' . $this->session->data['token'], 'SSL');

        $data['transaction'] = $this->url->link('sale/transactions', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_receivables'] = $this->url->link('sale/order_receivables', 'token=' . $this->session->data['token'], 'SSL');

        $data['customer_wallet'] = $this->url->link('wallets/customer_wallet', 'token=' . $this->session->data['token'], 'SSL');
        $data['vendor_wallet'] = $this->url->link('wallets/vendor_wallet', 'token=' . $this->session->data['token'], 'SSL');
        $data['admin_wallet'] = $this->url->link('wallets/admin_wallet', 'token=' . $this->session->data['token'], 'SSL');
        $data['drivers_list'] = $this->url->link('drivers/drivers_list', 'token=' . $this->session->data['token'], 'SSL');
        $data['vehicles_list'] = $this->url->link('vehicles/vehicles_list', 'token=' . $this->session->data['token'], 'SSL');
        $data['executives_list'] = $this->url->link('executives/executives_list', 'token=' . $this->session->data['token'], 'SSL');
        $data['orderprocessinggroup_list'] = $this->url->link('orderprocessinggroup/orderprocessinggroup_list', 'token=' . $this->session->data['token'], 'SSL');
        $data['orderprocessor_list'] = $this->url->link('orderprocessinggroup/orderprocessor', 'token=' . $this->session->data['token'], 'SSL');

        //blog
        $data['preturn_simple_blog_author'] = $this->user->hasPermission('access', 'simple_blog/author');
        $data['preturn_simple_blog_category'] = $this->user->hasPermission('access', 'simple_blog/category');
        $data['preturn_simple_blog_article'] = $this->user->hasPermission('access', 'simple_blog/article');
        $data['preturn_simple_blog_comment'] = $this->user->hasPermission('access', 'simple_blog/comment');
        $data['preturn_simple_blog_general_setting'] = $this->user->hasPermission('access', 'module/simple_blog');
        $data['preturn_simple_blog_category_setting'] = $this->user->hasPermission('access', 'module/simple_blog_category');
        $data['preturn_simple_blog_view_report'] = $this->user->hasPermission('access', 'simple_blog/report');

        //account
        $data['preturn_acc_profile'] = $this->user->hasPermission('access', 'account/profile');
        $data['preturn_acc_settings'] = $this->user->hasPermission('access', 'account/settings');
        $data['preturn_acc_packages'] = $this->user->hasPermission('access', 'account/packages');

        $data['preturn_cat_packages'] = $this->user->hasPermission('access', 'catalog/packages');

        //Catalogs
        $data['preturn_transactions_package'] = $this->user->hasPermission('access', 'transactions/package');
        $data['preturn_category'] = $this->user->hasPermission('access', 'catalog/category');
        $data['preturn_product_collection'] = $this->user->hasPermission('access', 'promotion/page');

        $data['preturn_checkout_question'] = $this->user->hasPermission('access', 'catalog/question');

        $data['preturn_recipe_category'] = $this->user->hasPermission('access', 'catalog/recipe_category');
        $data['preturn_recipe'] = $this->user->hasPermission('access', 'catalog/recipe');

        $data['preturn_help_category'] = $this->user->hasPermission('access', 'catalog/help_category');
        $data['preturn_help'] = $this->user->hasPermission('access', 'catalog/help');

        $data['preturn_product'] = $this->user->hasPermission('access', 'catalog/product');

        $data['preturn_design_notice'] = $this->user->hasPermission('access', 'design/notice');
        $data['preturn_design_blocks'] = $this->user->hasPermission('access', 'design/blocks');
        $data['preturn_general_products'] = $this->user->hasPermission('access', 'catalog/general');
        $data['preturn_review'] = $this->user->hasPermission('access', 'catalog/review');
        $data['preturn_information'] = $this->user->hasPermission('access', 'catalog/information');

        //Extensions
        $data['preturn_installer'] = $this->user->hasPermission('access', 'extension/installer');
        $data['preturn_modification'] = $this->user->hasPermission('access', 'extension/modification');
        $data['preturn_module'] = $this->user->hasPermission('access', 'extension/module');
        $data['preturn_shipping'] = $this->user->hasPermission('access', 'extension/shipping');
        $data['preturn_payment'] = $this->user->hasPermission('access', 'extension/payment');
        $data['preturn_total'] = $this->user->hasPermission('access', 'extension/total');
        $data['preturn_feed'] = $this->user->hasPermission('access', 'extension/feed');
        $data['preturn_marketplace'] = $this->user->hasPermission('access', 'extension/marketplace');

        //Sales
        $data['preturn_vendor_order'] = $this->user->hasPermission('access', 'sale/vendor_order');
        $data['preturn_order_dashboard'] = $this->user->hasPermission('access', 'sale/orderdashboard');
        $data['preturn_order'] = $this->user->hasPermission('access', 'sale/order');
        $data['preturn_order_delivaries'] = $this->user->hasPermission('access', 'sale/amitruckdelivaries');
        $data['preturn_order_product_missing'] = $this->user->hasPermission('access', 'sale/order_product_missing');
        $data['preturn_order_product_missing_products'] = $this->user->hasPermission('access', 'sale/order_product_missing_products');
        $data['preturn_order_recurring'] = $this->user->hasPermission('access', 'sale/recurring');
        $data['preturn_return'] = $this->user->hasPermission('access', 'sale/return');
        $data['preturn_customer'] = $this->user->hasPermission('access', 'sale/customer');
        $data['preturn_customer_otp'] = $this->user->hasPermission('access', 'sale/customer/customer_otp');
        $data['preturn_farmer'] = $this->user->hasPermission('access', 'sale/farmer');
        $data['preturn_farmertransactions'] = $this->user->hasPermission('access', 'sale/farmer_transactions');
        $data['preturn_customer_group'] = $this->user->hasPermission('access', 'sale/customer_group');
        $data['preturn_customer_ban_ip'] = $this->user->hasPermission('access', 'sale/customer_ban_ip');
        $data['preturn_customer_feedback'] = $this->user->hasPermission('access', 'sale/customer_feedback');
        $data['preturn_customer_issue'] = $this->user->hasPermission('access', 'sale/customer_issue');
        $data['preturn_account_manager'] = $this->user->hasPermission('access', 'sale/accountmanager');
        $data['preturn_customer_experience'] = $this->user->hasPermission('access', 'sale/customerexperience');
        $data['preturn_account_manager_customers'] = $this->user->hasPermission('access', 'sale/accountmanageruser');
        $data['preturn_account_manager_customer_orders'] = $this->user->hasPermission('access', 'sale/accountmanageruserorders');
        $data['preturn_custom_field'] = $this->user->hasPermission('access', 'sale/custom_field');
        $data['preturn_voucher'] = $this->user->hasPermission('access', 'sale/voucher');
        $data['preturn_voucher_theme'] = $this->user->hasPermission('access', 'sale/voucher_theme');
        $data['preturn_paypal'] = $this->user->hasPermission('access', 'payment/pp_express');
        $data['preturn_fast_order'] = $this->user->hasPermission('access', 'sale/fast_order');

        $data['preturn_transaction'] = $this->user->hasPermission('access', 'sale/transactions');
        $data['preturn_order_receivables'] = $this->user->hasPermission('access', 'sale/order_receivables');

        //Marketting
        $data['preturn_contact'] = $this->user->hasPermission('access', 'marketing/contact');
        $data['preturn_coupon'] = $this->user->hasPermission('access', 'marketing/coupon');
        $data['preturn_offer'] = $this->user->hasPermission('access', 'marketing/offer');
        $data['preturn_marketing'] = $this->user->hasPermission('access', 'marketing/marketing');

        //System
        $data['preturn_setting_seo'] = $this->user->hasPermission('access', 'setting/seo');

        $data['preturn_setting'] = $this->user->hasPermission('access', 'setting/setting');
        $data['preturn_store'] = $this->user->hasPermission('access', 'setting/store');

        $data['preturn_store_group'] = $this->user->hasPermission('access', 'setting/store_group');

        $data['preturn_store_type'] = $this->user->hasPermission('access', 'setting/store_type');

        $data['preturn_testimonial'] = $this->user->hasPermission('access', 'setting/testimonial');
        $data['preturn_newfeature'] = $this->user->hasPermission('access', 'setting/newfeature');
        $data['preturn_jobposition'] = $this->user->hasPermission('access', 'setting/jobposition');

        $data['preturn_design_offer'] = $this->user->hasPermission('access', 'design/offer');
        $data['preturn_design_slider'] = $this->user->hasPermission('access', 'design/slider');

        $data['preturn_shopper'] = $this->user->hasPermission('access', 'shopper/shopper');
        $data['preturn_shopper_permission'] = $this->user->hasPermission('access', 'shopper/shopper_permission');

        $data['preturn_user'] = $this->user->hasPermission('access', 'user/user');
        $data['preturn_user_permission'] = $this->user->hasPermission('access', 'user/user_permission');
        $data['preturn_user_api'] = $this->user->hasPermission('access', 'user/api');

        $data['preturn_shopper'] = $this->user->hasPermission('access', 'approvals/shopper');
        $data['preturn_approvals_product'] = $this->user->hasPermission('access', 'approvals/product');
        $data['preturn_enquiries'] = $this->user->hasPermission('access', 'approvals/enquiries');

        $data['preturn_vendor'] = $this->user->hasPermission('access', 'vendor/vendor');
        $data['preturn_vendor_permission'] = $this->user->hasPermission('access', 'vendor/vendor_permission');

        $data['preturn_email_template'] = $this->user->hasPermission('access', 'system/email_template');
        $data['preturn_language_override'] = $this->user->hasPermission('access', 'system/language_override');
        $data['preturn_localisation'] = $this->user->hasPermission('access', 'localisation/localisation');
        $data['preturn_language'] = $this->user->hasPermission('access', 'localisation/language');
        $data['preturn_currency'] = $this->user->hasPermission('access', 'localisation/currency');
        $data['preturn_stock_status'] = $this->user->hasPermission('access', 'localisation/stock_status');
        $data['preturn_order_status'] = $this->user->hasPermission('access', 'localisation/order_status');
        $data['preturn_app_order_status'] = $this->user->hasPermission('access', 'localisation/app_order_status');

        $data['preturn_app_order_status_mapping'] = $this->user->hasPermission('access', 'localisation/app_order_status');

        $data['preturn_delivery_statuses'] = $this->user->hasPermission('access', 'localisation/delivery_statuses');

        $data['enabled_delivery_statuses'] = $this->config->get('config_deliver_system_status');

        //echo "<pre>";print_r($this->config->get(''));die;
        $data['preturn_return_status'] = $this->user->hasPermission('access', 'localisation/return_status');
        $data['preturn_return_action'] = $this->user->hasPermission('access', 'localisation/return_action');
        $data['preturn_return_reason'] = $this->user->hasPermission('access', 'localisation/return_reason');
        $data['preturn_city'] = $this->user->hasPermission('access', 'localisation/city');
        $data['preturn_state'] = $this->user->hasPermission('access', 'localisation/state');
        $data['preturn_region'] = $this->user->hasPermission('access', 'localisation/region');
        $data['preturn_tax_class'] = $this->user->hasPermission('access', 'localisation/tax_class');

        //$data['preturn_tax_class'] = $this->user->hasPermission('access', 'localisation/tax_class');

        $data['preturn_tax_rate'] = $this->user->hasPermission('access', 'localisation/tax_rate');

        //Tools
        $data['preturn_backup'] = $this->user->hasPermission('access', 'tool/backup');

        $data['preturn_backup'] = $this->user->hasPermission('access', 'tool/clear_data');
        $data['preturn_error_log'] = $this->user->hasPermission('access', 'tool/error_log');
        $data['preturn_upload'] = $this->user->hasPermission('access', 'tool/upload');
        $data['preturn_export_import'] = $this->user->hasPermission('access', 'tool/export_import');
        $data['preturn_file_manager'] = $this->user->hasPermission('access', 'tool/file_manager');

        $data['preturn_report_vendor_order'] = $this->user->hasPermission('access', 'report/vendor_order');

        $data['preturn_report_vendor_orders'] = $this->user->hasPermission('access', 'report/vendor_orders');

        $data['preturn_report_vendor_returns'] = $this->user->hasPermission('access', 'report/vendor_returns');

        $data['preturn_report_combined_report'] = $this->user->hasPermission('access', 'report/combined_report');

        $data['preturn_report_store_sales'] = $this->user->hasPermission('access', 'report/store_sales');

        $data['preturn_report_vendor'] = $this->user->hasPermission('access', 'report/vendor');

        //Reports
        $data['preturn_report_shopper_order'] = $this->user->hasPermission('access', 'report/shopper_order');
        $data['preturn_report_shopper'] = $this->user->hasPermission('access', 'report/shopper');
        $data['preturn_report_income'] = $this->user->hasPermission('access', 'report/income');
        $data['preturn_sale_order'] = $this->user->hasPermission('access', 'report/sale_order');
        $data['preturn_account_manager_sale_order'] = $this->user->hasPermission('access', 'report/account_manager_sale_order');
        $data['preturn_sale_advanced'] = $this->user->hasPermission('access', 'report/sale_advanced');

        $data['preturn_sale_productmissing'] = $this->user->hasPermission('access', 'report/sale_productmissing');

        $data['preturn_sale_tax'] = $this->user->hasPermission('access', 'report/sale_tax');
        $data['preturn_sale_shipping'] = $this->user->hasPermission('access', 'report/sale_shipping');

        $data['preturn_sale_payment'] = $this->user->hasPermission('access', 'report/sale_payment');
        $data['preturn_sale_transaction'] = $this->user->hasPermission('access', 'report/sale_transaction');

        $data['preturn_sale_return'] = $this->user->hasPermission('access', 'report/sale_return');
        $data['preturn_sale_coupon'] = $this->user->hasPermission('access', 'report/sale_coupon');
        $data['preturn_product_viewed'] = $this->user->hasPermission('access', 'report/product_viewed');
        $data['preturn_product_purchased'] = $this->user->hasPermission('access', 'report/product_purchased');
        $data['preturn_customer_online'] = $this->user->hasPermission('access', 'report/customer_online');
        $data['preturn_customer_activity'] = $this->user->hasPermission('access', 'report/customer_activity');
        $data['preturn_farmer_activity'] = $this->user->hasPermission('access', 'report/farmer_activity');
        $data['preturn_account_manager_customer_activity'] = $this->user->hasPermission('access', 'report/account_manager_customer_activity');
        $data['preturn_account_manager_customer_online'] = $this->user->hasPermission('access', 'report/account_manager_customer_online');
        $data['preturn_user_activity'] = $this->user->hasPermission('access', 'report/user_activity');
        $data['preturn_customer_order'] = $this->user->hasPermission('access', 'report/customer_order');
        $data['preturn_customer_orderplaced'] = $this->user->hasPermission('access', 'report/customer_orderplaced');
        $data['preturn_customer_onboarded'] = $this->user->hasPermission('access', 'report/customer_onboarded');
        $data['preturn_customer_wallet'] = $this->user->hasPermission('access', 'report/customer_wallet');
        $data['preturn_customer_unordered'] = $this->user->hasPermission('access', 'report/customer_unordered');
        $data['preturn_account_manager_customer_order'] = $this->user->hasPermission('access', 'report/account_manager_customer_order');
        $data['preturn_customer_order_pattern'] = $this->user->hasPermission('access', 'report/customer_order_pattern');
        $data['preturn_customer_order_count'] = $this->user->hasPermission('access', 'report/customer_order_count');
        $data['preturn_inventory_daily_prices'] = $this->user->hasPermission('access', 'report/inventory_daily_prices');

        $data['preturn_customer_reward'] = $this->user->hasPermission('access', 'report/customer_reward');
        $data['preturn_customer_credit'] = $this->user->hasPermission('access', 'report/customer_credit');
        $data['preturn_marketing'] = $this->user->hasPermission('access', 'report/marketing');

        $data['preturn_vendor_product'] = $this->user->hasPermission('access', 'catalog/vendor_product');
        $data['preturn_vendor_commission'] = $this->user->hasPermission('access', 'report/commission');

        $data['preturn_customer_wallet'] = $this->user->hasPermission('access', 'wallets/customer_wallet');
        $data['preturn_vendor_wallet'] = $this->user->hasPermission('access', 'wallets/vendor_wallet');
        $data['preturn_admin_wallet'] = $this->user->hasPermission('access', 'wallets/admin_wallet');

        $data['preturn_drivers'] = $this->user->hasPermission('access', 'drivers/drivers_list');
        $data['preturn_vehicles'] = $this->user->hasPermission('access', 'vehicles/vehicles_list');
        $data['preturn_executives'] = $this->user->hasPermission('access', 'executives/executives_list');
        $data['preturn_orderprocessinggroups'] = $this->user->hasPermission('access', 'orderprocessinggroup/orderprocessinggroup_list');
        $data['preturn_orderprocessor'] = $this->user->hasPermission('access', 'orderprocessinggroup/orderprocessor');
        return $this->load->view('common/menu.tpl', $data);
    }

    private function vendor_menu() {
        $this->load->model('account/settings');
        $vendor_info = $this->model_account_settings->getUser($this->user->getId());

        $this->load->language('common/menu');

        if ($vendor_info) {
            $vendor_id = $vendor_info['user_id'];
        } else {
            $vendor_id = 0;
        }

        $data = $this->language->all();

        $data['link_vendor_info'] = $this->url->link('vendor/vendor/info', 'token=' . $this->session->data['token'] . '&vendor_id=' . $vendor_id, 'SSL');

        $data['acc_profile'] = $this->url->link('account/profile', 'token=' . $this->session->data['token'], 'SSL');
        $data['acc_settings'] = $this->url->link('account/settings', 'token=' . $this->session->data['token'], 'SSL');
        $data['acc_password'] = $this->url->link('account/settings/password', 'token=' . $this->session->data['token'], 'SSL');
        $data['acc_packages'] = $this->url->link('account/packages', 'token=' . $this->session->data['token'], 'SSL');

        $data['general_products'] = $this->url->link('catalog/general', 'token=' . $this->session->data['token'], 'SSL');
        $data['dashboard'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_income'] = $this->url->link('report/income', 'token=' . $this->session->data['token'], 'SSL');

        $data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
        $data['export_import'] = $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL');

        $data['clear_data'] = $this->url->link('tool/clear_data', 'token=' . $this->session->data['token'], 'SSL');

        $data['file_manager'] = $this->url->link('tool/file_manager', 'token=' . $this->session->data['token'], 'SSL');
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_delivaries'] = $this->url->link('sale/amitruckdelivaries', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_product_missing'] = $this->url->link('sale/order_product_missing', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_product_missing_products'] = $this->url->link('sale/order_product_missing_products', 'token=' . $this->session->data['token'], 'SSL');
        $data['order_dashboard'] = $this->url->link('sale/orderdashboard', 'token=' . $this->session->data['token'], 'SSL');

        $data['product'] = $this->url->link('catalog/vendor_product', 'token=' . $this->session->data['token'], 'SSL');
        $data['fast_order'] = $this->url->link('sale/fast_order', 'token=' . $this->session->data['token'] . '&filter_order_status=2,15,18,3,5&filter_order_day=today', 'SSL');

        $data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_sale_order'] = $this->url->link('report/account_manager_sale_order', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_advanced'] = $this->url->link('report/sale_advanced', 'token=' . $this->session->data['token'] . '&filter_order_status_id=5', 'SSL');

        $data['report_sale_productmissing'] = $this->url->link('report/sale_productmissing', 'token=' . $this->session->data['token'] . '&filter_order_status_id=5', 'SSL');

        $data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_payment'] = $this->url->link('report/sale_payment', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_transaction'] = $this->url->link('report/sale_transaction', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_activity'] = $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_farmer_activity'] = $this->url->link('report/farmer_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_customer_activity'] = $this->url->link('report/account_manager_customer_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_customer_online'] = $this->url->link('report/account_manager_customer_online', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_user_activity'] = $this->url->link('report/user_activity', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_orderplaced'] = $this->url->link('report/customer_orderplaced', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_onboarded'] = $this->url->link('report/customer_onboarded', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_wallet'] = $this->url->link('report/customer_wallet', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_unordered'] = $this->url->link('report/customer_unordered', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_account_manager_customer_order'] = $this->url->link('report/account_manager_customer_order', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_statement'] = $this->url->link('report/customer_order/statement', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_boughtproducts'] = $this->url->link('report/customer_boughtproducts', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_order_pattern'] = $this->url->link('report/customer_order_pattern', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_order_count'] = $this->url->link('report/customer_order_count', 'token=' . $this->session->data['token'], 'SSL');
        $data['report_customer_inventory_daily_prices'] = $this->url->link('report/inventory_daily_prices', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_commission'] = $this->url->link('report/vendor_commission', 'token=' . $this->session->data['token'], 'SSL');

        $data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');

        $data['setting'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL');
        $data['store'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
        $data['store_group'] = $this->url->link('setting/store_group', 'token=' . $this->session->data['token'], 'SSL');
        $data['store_type'] = $this->url->link('setting/store_type', 'token=' . $this->session->data['token'], 'SSL');
        $data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_order'] = $this->url->link('report/vendor_order', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_orders'] = $this->url->link('report/vendor_orders', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor_returns'] = $this->url->link('report/vendor_returns', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_combined_report'] = $this->url->link('report/combined_report', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_store_sales'] = $this->url->link('report/store_sales', 'token=' . $this->session->data['token'], 'SSL');

        $data['report_vendor'] = $this->url->link('report/vendor', 'token=' . $this->session->data['token'], 'SSL');

        //account
        $data['preturn_acc_profile'] = $this->user->hasPermission('access', 'account/profile');
        $data['preturn_acc_settings'] = $this->user->hasPermission('access', 'account/settings');
        $data['preturn_acc_packages'] = $this->user->hasPermission('access', 'account/packages');

        $data['preturn_product'] = $this->user->hasPermission('access', 'catalog/product');

        $data['preturn_sale_advanced'] = $this->user->hasPermission('access', 'report/sale_advanced');
        $data['preturn_sale_productmissing'] = $this->user->hasPermission('access', 'report/sale_productmissing');

        $data['preturn_general_products'] = $this->user->hasPermission('access', 'catalog/general');

        //Sales
        $data['preturn_fast_order'] = $this->user->hasPermission('access', 'sale/fast_order');
        $data['preturn_order'] = $this->user->hasPermission('access', 'sale/vendor_order');
        $data['preturn_order_dashboard'] = $this->user->hasPermission('access', 'sale/orderdashboard');
        $data['preturn_order_recurring'] = $this->user->hasPermission('access', 'sale/recurring');
        $data['preturn_return'] = $this->user->hasPermission('access', 'sale/return');

        //System
        $data['preturn_store'] = $this->user->hasPermission('access', 'setting/store');
        $data['preturn_store_group'] = $this->user->hasPermission('access', 'setting/store_group');
        $data['preturn_store_type'] = $this->user->hasPermission('access', 'setting/store_type');

        //Tools
        $data['preturn_error_log'] = $this->user->hasPermission('access', 'tool/error_log');
        $data['preturn_upload'] = $this->user->hasPermission('access', 'tool/upload');
        $data['preturn_export_import'] = $this->user->hasPermission('access', 'tool/export_import');
        $data['preturn_file_manager'] = $this->user->hasPermission('access', 'tool/file_manager');

        //Reports
        $data['preturn_report_income'] = $this->user->hasPermission('access', 'report/income');
        $data['preturn_sale_order'] = $this->user->hasPermission('access', 'report/sale_order');
        $data['preturn_account_manager_sale_order'] = $this->user->hasPermission('access', 'report/account_manager_sale_order');
        $data['preturn_sale_advanced'] = $this->user->hasPermission('access', 'report/sale_advanced');

        $data['preturn_sale_productmissing'] = $this->user->hasPermission('access', 'report/sale_productmissing');

        $data['preturn_sale_tax'] = $this->user->hasPermission('access', 'report/sale_tax');
        $data['preturn_sale_shipping'] = $this->user->hasPermission('access', 'report/sale_shipping');
        $data['preturn_sale_payment'] = $this->user->hasPermission('access', 'report/sale_payment');

        $data['preturn_sale_transaction'] = $this->user->hasPermission('access', 'report/sale_transaction');

        $data['preturn_sale_return'] = $this->user->hasPermission('access', 'report/sale_return');
        $data['preturn_sale_coupon'] = $this->user->hasPermission('access', 'report/sale_coupon');
        $data['preturn_product_viewed'] = $this->user->hasPermission('access', 'report/product_viewed');
        $data['preturn_product_purchased'] = $this->user->hasPermission('access', 'report/product_purchased');
        $data['preturn_customer_online'] = $this->user->hasPermission('access', 'report/customer_online');
        $data['preturn_customer_activity'] = $this->user->hasPermission('access', 'report/customer_activity');
        $data['preturn_farmer_activity'] = $this->user->hasPermission('access', 'report/farmer_activity');
        $data['preturn_account_manager_customer_activity'] = $this->user->hasPermission('access', 'report/account_manager_customer_activity');
        $data['preturn_account_manager_customer_online'] = $this->user->hasPermission('access', 'report/account_manager_customer_online');
        $data['preturn_user_activity'] = $this->user->hasPermission('access', 'report/user_activity');
        $data['preturn_customer_order'] = $this->user->hasPermission('access', 'report/customer_order');
        $data['preturn_account_manager_customer_order'] = $this->user->hasPermission('access', 'report/account_manager_customer_order');
        $data['preturn_customer_reward'] = $this->user->hasPermission('access', 'report/customer_reward');
        $data['preturn_customer_credit'] = $this->user->hasPermission('access', 'report/customer_credit');
        $data['preturn_customer_statement'] = $this->user->hasPermission('access', 'report/customer_statement');
        $data['preturn_customer_boughtproducts'] = $this->user->hasPermission('access', 'report/customer_boughtproducts');
        $data['preturn_customer_order_pattern'] = $this->user->hasPermission('access', 'report/customer_order_pattern');
        $data['preturn_customer_order_count'] = $this->user->hasPermission('access', 'report/customer_order_count');
        $data['preturn_inventory_daily_prices'] = $this->user->hasPermission('access', 'report/inventory_daily_prices');

        $data['preturn_marketing'] = $this->user->hasPermission('access', 'report/marketing');

        $data['preturn_report_vendor_order'] = $this->user->hasPermission('access', 'report/vendor_order');

        $data['preturn_report_vendor_orders'] = $this->user->hasPermission('access', 'report/vendor_orders');
        $data['preturn_report_vendor_returns'] = $this->user->hasPermission('access', 'report/vendor_returns');
        $data['preturn_report_combined_report'] = $this->user->hasPermission('access', 'report/combined_report');

        $data['preturn_report_store_sales'] = $this->user->hasPermission('access', 'report/store_sales');

        $data['preturn_report_vendor'] = $this->user->hasPermission('access', 'report/vendor');

        //echo "<pre>";print_r($data['preturn_report_vendor']);die;
        $data['preturn_report_vendor_com'] = $this->user->hasPermission('access', 'report/vendor_commission');

        return $this->load->view('common/vendor_menu.tpl', $data);
    }

}
