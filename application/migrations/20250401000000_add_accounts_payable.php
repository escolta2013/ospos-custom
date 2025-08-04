<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_accounts_payable extends CI_Migration {

    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        error_log('Starting migration to add accounts payable fields to expenses');

        // Definir los nuevos campos
        $fields = array(
            'payment_status' => array(
                'type' => 'ENUM("pending", "partial", "paid")',
                'default' => 'pending',
                'null' => FALSE // Aseguramos que siempre tenga un valor
            ),
            'amount_due' => array(
                'type' => 'DECIMAL(10,2)',
                'null' => TRUE // Permitimos nulos para casos iniciales
            ),
            'due_date' => array(
                'type' => 'DATE',
                'null' => TRUE // Permitimos nulos para flexibilidad
            )
        );

        // Agregar los campos a la tabla expenses
        $this->dbforge->add_column('expenses', $fields);

        // Actualizar datos existentes para mantener consistencia
        $this->db->query("UPDATE ospos_expenses SET amount_due = amount WHERE amount_due IS NULL");
        $this->db->query("UPDATE ospos_expenses SET payment_status = 'paid' WHERE amount_due = 0");

        error_log('Migration to add accounts payable fields completed');
    }

    public function down()
    {
        error_log('Reverting migration to remove accounts payable fields from expenses');

        // Eliminar los campos en orden inverso para evitar problemas de dependencias
        $this->dbforge->drop_column('expenses', 'due_date');
        $this->dbforge->drop_column('expenses', 'amount_due');
        $this->dbforge->drop_column('expenses', 'payment_status');

        error_log('Reverting migration completed');
    }
}