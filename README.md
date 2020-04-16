 Installation
 
 1) Git Clone Repo
 
 2) Import mvgfinal.sql.zip via PhpMyAdmin or SequelPro or any other MySQL client
 
 3) In config.php replace db details with yours
 
 4) gitignore system/cache, image/cache folders while pushing
 
 Voila!! Open browser 
 
 1) Frontend : http://localhost/multivendorgrocery
 
 2) Backend : http://localhost/multivendorgrocery/admin




challenges:

1- <?php if($product_status->status == 'In-Transit') { $is_true = true; ?>
            <span class="badge badge-info">
        <?= $text_intransit ?>
	    </span>
	<?php } ?> 

	== ‘In-Transit’. how will you replace it with language variable?

2- We will redo email templates architecture

3- Hide Transactions in Admin and make a note of it

4- 
//http://localhost/grocerypik/index.php/api/customer/checkout/DeliveryTimeslot?shipping_method=normal.normal&store_id=8

5- hardcoded admin side timezone in admin/index/php

6- SET GLOBAL time_zone = '+3:00'; mysql timezone need to be set 
#
1- fb add get custom tshirt printed .. click to place order 
2- marketing available for limited time in your city
3- at least a page where he can place order to 


upload design


@@@@@@@@

CRON JOBS:

a) runs every 5 min to create DS orders

*/5 *  * * *  curl https://demo.grocerypik.com/index.php?path=account/account/createDSrequest >> /var/www/html/demo.grocerypik.com/system/log/error.log 2>&1

@@@@
