/** 5 June 2020 **/

CREATE TABLE `hf7_farmer` (
 `farmer_id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(200) CHARACTER SET utf8 NOT NULL,
 `email_id` varchar(96) CHARACTER SET utf8 NOT NULL,
 `farm_address` varchar(2000) CHARACTER SET utf8 NOT NULL,
 `contact_number` varchar(32) CHARACTER SET utf8 NOT NULL,
 `farmer_type` varchar(100) CHARACTER SET utf8 NOT NULL,
 `work_on_farm` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
 `country` varchar(32) CHARACTER SET utf8 NOT NULL,
 `village` varchar(32) CHARACTER SET utf8 NOT NULL,
 `business_entity` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
 `name_of_farm` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
 `total` decimal(15,4) NOT NULL,
 `crop_type` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
 `crops_grown` text CHARACTER SET utf8,
 PRIMARY KEY (`farmer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1


/** 8 June 2020 **/

CREATE TABLE `hf7_product_inventory_history` (
 `product_history_id` int(11) NOT NULL AUTO_INCREMENT,
 `product_store_id` int(11) NOT NULL,
 `product_id` int(11) NOT NULL,
 `procured_qty` int(11) NOT NULL,
 `rejected_qty` int(11) DEFAULT 0,
 `prev_qty` int(11) NOT NULL,
 `current_qty` int(11) NOT NULL,
 `product_name` varchar(200) CHARACTER SET utf8 NOT NULL,
 `date_added` datetime NOT NULL,
 PRIMARY KEY (`product_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1


CREATE TABLE `hf7_product_category_prices` (
  `product_category_price_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `price_category` varchar(250) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_category_price_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8