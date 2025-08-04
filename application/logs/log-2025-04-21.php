<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-04-21 14:55:09 --> Could not find the language line "receivings_Izcaragua (TBD)"
ERROR - 2025-04-21 14:55:09 --> Could not find the language line "receivings_VAGC (TBD)"
ERROR - 2025-04-21 14:55:09 --> Could not find the language line "sales_Izcaragua (TBD)"
ERROR - 2025-04-21 14:55:09 --> Could not find the language line "sales_Lagunita (TBD)"
ERROR - 2025-04-21 14:55:09 --> Could not find the language line "sales_VAGC (TBD)"
ERROR - 2025-04-21 15:01:55 --> Could not find the language line "receivings_Izcaragua (TBD)"
ERROR - 2025-04-21 15:01:55 --> Could not find the language line "receivings_VAGC (TBD)"
ERROR - 2025-04-21 15:01:55 --> Could not find the language line "sales_Izcaragua (TBD)"
ERROR - 2025-04-21 15:01:55 --> Could not find the language line "sales_Lagunita (TBD)"
ERROR - 2025-04-21 15:01:55 --> Could not find the language line "sales_VAGC (TBD)"
ERROR - 2025-04-21 15:02:40 --> Could not find the language line "receivings_Izcaragua (TBD)"
ERROR - 2025-04-21 15:02:40 --> Could not find the language line "receivings_VAGC (TBD)"
ERROR - 2025-04-21 15:02:40 --> Could not find the language line "sales_Izcaragua (TBD)"
ERROR - 2025-04-21 15:02:40 --> Could not find the language line "sales_Lagunita (TBD)"
ERROR - 2025-04-21 15:02:40 --> Could not find the language line "sales_VAGC (TBD)"
ERROR - 2025-04-21 15:32:46 --> Could not find the language line "receivings_Izcaragua (TBD)"
ERROR - 2025-04-21 15:32:46 --> Could not find the language line "receivings_VAGC (TBD)"
ERROR - 2025-04-21 15:32:46 --> Could not find the language line "sales_Izcaragua (TBD)"
ERROR - 2025-04-21 15:32:46 --> Could not find the language line "sales_Lagunita (TBD)"
ERROR - 2025-04-21 15:32:46 --> Could not find the language line "sales_VAGC (TBD)"
ERROR - 2025-04-21 15:33:04 --> Severity: error --> Exception: syntax error, unexpected 'reports_detailed_reports' (T_STRING), expecting ')' /home/dxhscqvn/public_html/mproshop_foca/application/views/reports/listing.php 59
ERROR - 2025-04-21 15:44:48 --> Could not find the language line "receivings_Izcaragua (TBD)"
ERROR - 2025-04-21 15:44:48 --> Could not find the language line "receivings_VAGC (TBD)"
ERROR - 2025-04-21 15:44:48 --> Could not find the language line "sales_Izcaragua (TBD)"
ERROR - 2025-04-21 15:44:48 --> Could not find the language line "sales_Lagunita (TBD)"
ERROR - 2025-04-21 15:44:48 --> Could not find the language line "sales_VAGC (TBD)"
ERROR - 2025-04-21 17:18:58 --> 404 Page Not Found: Reports/summary_accounts_payable_by_category
ERROR - 2025-04-21 17:26:09 --> 404 Page Not Found: Reports/summary_accounts_payable_by_category
ERROR - 2025-04-21 17:26:18 --> 404 Page Not Found: Reports/summary_accounts_payable_by_category
ERROR - 2025-04-21 17:26:28 --> 404 Page Not Found: Reports/summary_accounts_payable_by_category
ERROR - 2025-04-21 17:26:35 --> 404 Page Not Found: Reports/summary_accounts_payable_by_category
ERROR - 2025-04-21 17:30:47 --> 404 Page Not Found: Reports/summary_accounts_payable_by_category
ERROR - 2025-04-21 17:32:19 --> Severity: error --> Exception: syntax error, unexpected '<', expecting end of file /home/dxhscqvn/public_html/mproshop_foca/application/views/reports/listing.php 30
ERROR - 2025-04-21 17:32:24 --> Severity: error --> Exception: syntax error, unexpected '<', expecting end of file /home/dxhscqvn/public_html/mproshop_foca/application/views/reports/listing.php 30
ERROR - 2025-04-21 17:32:33 --> Severity: error --> Exception: syntax error, unexpected '<', expecting end of file /home/dxhscqvn/public_html/mproshop_foca/application/views/reports/listing.php 30
ERROR - 2025-04-21 17:35:53 --> Query error: Unknown column 'expenses.amount' in 'SELECT' - Invalid query: SELECT `ospos_expenses`.`expense_category_id` AS `category_id`, `ospos_expense_categories`.`category_name` AS `category_name`, SUM(expenses.amount + expenses.tax_amount) AS total_invoiced, SUM(expenses.amount_due) AS total_due, MIN(expenses.due_date)   AS next_due_date
FROM `ospos_expenses`
LEFT JOIN `ospos_expense_categories` ON `ospos_expense_categories`.`expense_category_id` = `ospos_expenses`.`expense_category_id`
WHERE `payment_status` != 'paid'
GROUP BY `ospos_expenses`.`expense_category_id`
ERROR - 2025-04-21 17:35:53 --> Severity: error --> Exception: Call to a member function result_array() on bool /home/dxhscqvn/public_html/mproshop_foca/application/models/reports/Accounts_payable_by_category.php 33
ERROR - 2025-04-21 17:38:08 --> Query error: Unknown column 'expenses.amount' in 'SELECT' - Invalid query: SELECT `ospos_expenses`.`expense_category_id` AS `category_id`, `ospos_expense_categories`.`category_name` AS `category_name`, SUM(expenses.amount + expenses.tax_amount) AS total_invoiced, SUM(expenses.amount_due) AS total_due, MIN(expenses.due_date)   AS next_due_date
FROM `ospos_expenses`
LEFT JOIN `ospos_expense_categories` ON `ospos_expense_categories`.`expense_category_id` = `ospos_expenses`.`expense_category_id`
WHERE `payment_status` != 'paid'
GROUP BY `ospos_expenses`.`expense_category_id`
ERROR - 2025-04-21 17:38:08 --> Severity: error --> Exception: Call to a member function result_array() on bool /home/dxhscqvn/public_html/mproshop_foca/application/models/reports/Accounts_payable_by_category.php 33
ERROR - 2025-04-21 17:56:28 --> Severity: error --> Exception: Call to undefined function lang() /home/dxhscqvn/public_html/mproshop_foca/application/views/reports/accounts_payable_by_category.php 1
ERROR - 2025-04-21 17:57:09 --> Severity: error --> Exception: Call to undefined function lang() /home/dxhscqvn/public_html/mproshop_foca/application/views/reports/accounts_payable_by_category.php 1
ERROR - 2025-04-21 18:10:35 --> 404 Page Not Found: Reports/summary_category
ERROR - 2025-04-21 18:11:21 --> 404 Page Not Found: Reports/graphical_summary_accounts_payable_by_category
ERROR - 2025-04-21 19:24:52 --> 404 Page Not Found: Images/menubar
ERROR - 2025-04-21 19:24:58 --> 404 Page Not Found: Images/menubar
ERROR - 2025-04-21 19:25:02 --> 404 Page Not Found: Images/menubar
ERROR - 2025-04-21 19:25:11 --> 404 Page Not Found: Images/menubar
ERROR - 2025-04-21 19:25:16 --> Severity: error --> Exception: Unable to locate the model you have specified: Summary_expenses_categories /home/dxhscqvn/public_html/mproshop_foca/vendor/codeigniter/framework/system/core/Loader.php 348
ERROR - 2025-04-21 19:34:49 --> Severity: error --> Exception: Unable to locate the model you have specified: Summary_expenses_categories /home/dxhscqvn/public_html/mproshop_foca/vendor/codeigniter/framework/system/core/Loader.php 348
ERROR - 2025-04-21 20:03:30 --> Severity: error --> Exception: syntax error, unexpected '$tabular' (T_VARIABLE), expecting function (T_FUNCTION) or const (T_CONST) /home/dxhscqvn/public_html/mproshop_foca/application/controllers/Reports.php 177
ERROR - 2025-04-21 20:07:30 --> Severity: error --> Exception: syntax error, unexpected '$tabular' (T_VARIABLE), expecting function (T_FUNCTION) or const (T_CONST) /home/dxhscqvn/public_html/mproshop_foca/application/controllers/Reports.php 177
ERROR - 2025-04-21 22:13:06 --> Severity: error --> Exception: /home/dxhscqvn/public_html/mproshop_foca/application/models/reports/Accounts_payable_by_category.php exists, but doesn't declare class Accounts_payable_by_category /home/dxhscqvn/public_html/mproshop_foca/vendor/codeigniter/framework/system/core/Loader.php 340
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> reset() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 41
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 42
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 43
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 44
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 45
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 46
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 48
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> reset() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 41
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 42
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 43
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 44
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 45
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 46
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 48
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> reset() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 41
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 42
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 43
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 44
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 45
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 46
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 48
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> reset() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 41
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 42
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 43
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 44
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> key() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 45
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 46
ERROR - 2025-04-21 22:23:51 --> Severity: Warning --> current() expects parameter 1 to be array, string given /home/dxhscqvn/public_html/mproshop_foca/application/helpers/tabular_helper.php 48
ERROR - 2025-04-21 22:38:12 --> Severity: error --> Exception: Unable to locate the model you have specified: Accounts_payable_by_category /home/dxhscqvn/public_html/mproshop_foca/vendor/codeigniter/framework/system/core/Loader.php 348
ERROR - 2025-04-21 23:23:41 --> Severity: error --> Exception: Call to undefined method Reports::report_tabular() /home/dxhscqvn/public_html/mproshop_foca/application/controllers/Reports.php 155
