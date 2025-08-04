-- Para cedula cliente
ALTER TABLE 
`ospos_people` ADD COLUMN IF NOT EXISTS `cedula` VARCHAR(15) NOT NULL DEFAULT '0' AFTER `last_name`;

-- Para registro de metodos de pago moneda alternativa Tasa y Monto Convert
ALTER TABLE
    `ospos_sales_payments` ADD COLUMN IF NOT EXISTS `T1` DECIMAL(15, 2) NOT NULL DEFAULT '0' AFTER `payment_amount`,
    ADD COLUMN IF NOT EXISTS `M1` DECIMAL(15, 2) NOT NULL DEFAULT '0' AFTER `T1`;

UPDATE
    `ospos_app_config`
SET
    `value` = 'Morsas'
WHERE
    `ospos_app_config`.`key` = 'payment_options_order';