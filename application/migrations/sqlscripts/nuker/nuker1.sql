ALTER TABLE 
`ospos_employees` ADD COLUMN IF NOT EXISTS `is_admin` INT(1)
 NOT NULL DEFAULT '0' AFTER `person_id`; 

 ALTER TABLE
    `ospos_sales_payments_tocredit` 
    ADD COLUMN IF NOT EXISTS  `T1` DECIMAL(15, 4) 
    NOT NULL DEFAULT '0' AFTER `payment_amount`;