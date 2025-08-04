<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-04-28 09:55:04 --> DEBUG: stock_location en Inventario = 
ERROR - 2025-04-28 10:06:41 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 10:06:41')
ERROR - 2025-04-28 10:28:13 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 10:28:13')
ERROR - 2025-04-28 11:46:48 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 11:46:48')
ERROR - 2025-04-28 11:49:40 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 11:49:40')
ERROR - 2025-04-28 11:52:54 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 11:52:54')
ERROR - 2025-04-28 12:31:42 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 12:31:42')
ERROR - 2025-04-28 12:33:28 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 12:33:28')
ERROR - 2025-04-28 12:35:59 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dxhscqvn_ospos`.`ospos_inventory_records`, CONSTRAINT `fk_item` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE) - Invalid query: INSERT INTO `ospos_inventory_records` (`inventory_date`) VALUES ('2025-04-28 12:35:59')
ERROR - 2025-04-28 13:15:45 --> Query error: Duplicate entry 'PERDIDAS ' for key 'category_name' - Invalid query: INSERT INTO `ospos_expense_categories` (`category_name`, `category_description`) VALUES ('PERDIDAS ', '')
ERROR - 2025-04-28 13:42:39 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%B%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%B%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%B%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%B%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%B%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%B%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%B%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-04-01 00:00:00' AND '2025-04-28 23:59:59'
ERROR - 2025-04-28 13:42:39 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
ERROR - 2025-04-28 13:42:40 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%BOLSA TEES%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%BOLSA TEES%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%BOLSA TEES%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%BOLSA TEES%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%BOLSA TEES%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%BOLSA TEES%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%BOLSA TEES%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-04-01 00:00:00' AND '2025-04-28 23:59:59'
ERROR - 2025-04-28 13:42:40 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
ERROR - 2025-04-28 13:42:43 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%BOLSA T%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%BOLSA T%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%BOLSA T%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%BOLSA T%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%BOLSA T%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%BOLSA T%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%BOLSA T%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-04-01 00:00:00' AND '2025-04-28 23:59:59'
ERROR - 2025-04-28 13:42:43 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
