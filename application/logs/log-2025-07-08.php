<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-07-08 15:57:53 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%SERV%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%SERV%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%SERV%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%SERV%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%SERV%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%SERV%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%SERV%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-06-01 00:00:00' AND '2025-06-30 23:59:59'
ERROR - 2025-07-08 15:57:53 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
ERROR - 2025-07-08 16:04:40 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%DOO%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%DOO%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%DOO%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%DOO%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%DOO%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%DOO%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%DOO%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-06-01 00:00:00' AND '2025-06-30 23:59:59'
ERROR - 2025-07-08 16:04:40 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
ERROR - 2025-07-08 16:04:41 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%DOODLE%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%DOODLE%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%DOODLE%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%DOODLE%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%DOODLE%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%DOODLE%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%DOODLE%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-06-01 00:00:00' AND '2025-06-30 23:59:59'
ERROR - 2025-07-08 16:04:41 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
