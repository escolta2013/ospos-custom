<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-05-02 11:16:22 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%SRIXON%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%SRIXON%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-05-01 00:00:00' AND '2025-05-02 23:59:59'
ERROR - 2025-05-02 11:16:22 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
ERROR - 2025-05-02 11:16:25 --> Query error: Unknown column 'ospos_employees.first_name' in 'WHERE' - Invalid query: SELECT SUM(amount) as total_expenses
FROM `ospos_expenses`
WHERE   (
`ospos_employees`.`first_name` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expenses`.`date` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_employees`.`last_name` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expenses`.`payment_type` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expenses`.`amount` LIKE '%SRIXON%' ESCAPE '!'
OR  `ospos_expense_categories`.`category_name` LIKE '%SRIXON%' ESCAPE '!'
OR  CONCAT(employees.first_name, " ", employees.last_name) LIKE '%SRIXON%' ESCAPE '!'
 )
AND `deleted` = 0
AND `date` BETWEEN '2025-04-03 00:00:00' AND '2025-05-02 23:59:59'
ERROR - 2025-05-02 11:16:25 --> Severity: error --> Exception: Call to a member function row() on bool /home/dxhscqvn/public_html/mproshop/application/models/Expense.php 363
