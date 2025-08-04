# Modulo Cuentas por cobrar
INSERT IGNORE INTO `ospos_modules`(
    `name_lang_key`,
    `desc_lang_key`,
    `sort`,
    `module_id`
)
VALUES(
    'module_billtocollects',
    'module_billtocollects_desc',
    '988',
    'billtocollects'
);
INSERT IGNORE INTO `ospos_permissions`(`permission_id`, `module_id`)
VALUES(
    'billtocollects',
    'billtocollects'
);

INSERT IGNORE INTO `ospos_grants`(
    `permission_id`,
    `person_id`,
    `menu_group`
)
VALUES('billtocollects', 1, 'home');


# Estados De Cuenta
INSERT IGNORE INTO `ospos_modules`(
    `name_lang_key`,
    `desc_lang_key`,
    `sort`,
    `module_id`
)
VALUES(
    'module_statements',
    'module_statements_desc',
    '988',
    'statements'
);

INSERT IGNORE INTO `ospos_permissions`(
    `permission_id`,
    `module_id`,
    `location_id`
)
VALUES('statements', 'statements', NULL);

INSERT IGNORE INTO `ospos_grants`(
    `permission_id`,
    `person_id`,
    `menu_group`
)
VALUES('statements', 1, 'home');

 
 
 
ALTER TABLE 
`ospos_sales` ADD COLUMN IF NOT EXISTS  `abonado` DECIMAL(15,2) NOT NULL DEFAULT '0' AFTER `tasa`;

ALTER TABLE
 `ospos_sales` ADD COLUMN IF NOT EXISTS `status_credito` INT(1) NOT NULL DEFAULT '0' AFTER `abonado`; 

ALTER TABLE
    `ospos_sales` ADD COLUMN IF NOT EXISTS  `status_abonado` INT(1) NOT NULL DEFAULT '0' AFTER `status_credito`;

ALTER TABLE 
`ospos_sales` ADD COLUMN IF NOT EXISTS  `monto` DECIMAL(15,2) NOT NULL DEFAULT '0' AFTER `comment`; 

ALTER TABLE
 `ospos_sales_payments` ADD COLUMN IF NOT EXISTS `customer_id` INT NULL DEFAULT NULL AFTER `employee_id`; 

 INSERT INTO `ospos_app_config` (`key`, `value`) VALUES ('auto_cambio_tasa', '1'); 

 INSERT INTO `ospos_app_config` (`key`, `value`) VALUES ('tasa_ref', '1'); 


  CREATE TABLE `ospos_sales_payments_tocredit` (
  `sale_id` int(10) NOT NULL,
  `payment_id` bigint(20) DEFAULT NULL,
  `payment_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_tocredit` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `payment_type` varchar(50) DEFAULT 'Adeudado $',
  `employee_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `payment_amount_credit` decimal(15,2) NOT NULL,
  `status_credito` int(11) NOT NULL DEFAULT 0,
  `sale_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

 
ALTER TABLE `ospos_sales_payments_tocredit`
  ADD PRIMARY KEY (`sale_id`,`payment_tocredit`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `payment_time` (`payment_type`); 

ALTER TABLE `ospos_sales_payments_tocredit`
  ADD CONSTRAINT `ospos_sales_payments_tocredit_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`);
 
 
INSERT INTO `ospos_permissions` (`permission_id`, `module_id`, `location_id`) VALUES ('statements_dell', 'statements', NULL);

INSERT INTO `ospos_grants` (`permission_id`, `person_id`, `menu_group`) VALUES ('statements_dell', '1', 'statements'); 

INSERT INTO `ospos_permissions` (`permission_id`, `module_id`, `location_id`) VALUES ('statements_receipt', 'statements', NULL);

INSERT INTO `ospos_grants` (`permission_id`, `person_id`, `menu_group`) VALUES ('statements_receipt', '1', 'statements');