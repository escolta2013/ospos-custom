<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once("Summary_report.php");

class Summary_accounts_payable_by_category extends Summary_report
{
    protected function _get_data_columns()
    {
        return array(
            array('category_name'  => $this->lang->line('reports_expenses_category')),
            array('total_invoiced' => $this->lang->line('reports_total_invoiced'), 'sorter'=>'number_sorter'),
            array('total_due'      => $this->lang->line('reports_total_due'),      'sorter'=>'number_sorter'),
            array('next_due_date'  => $this->lang->line('reports_next_due_date'))
        );
    }

    public function getData(array $inputs)
    {
        $this->db->select('
            expense_categories.category_name        AS category_name,
            SUM(expenses.amount + expenses.tax_amount) AS total_invoiced,
            SUM(expenses.amount_due)                  AS total_due,
            MIN(expenses.due_date)                    AS next_due_date
        ');
        $this->db->from('expenses AS expenses');
        $this->db->join('expense_categories AS expense_categories', 'expense_categories.expense_category_id = expenses.expense_category_id', 'LEFT');
        // rango fechas idéntico a summary_expenses_categories…
        if (empty($this->config->item('date_or_time_format')))
        {
            $this->db->where('DATE(expenses.date) BETWEEN ' . 
                $this->db->escape($inputs['start_date']) . 
                ' AND ' . 
                $this->db->escape($inputs['end_date'])
            );
        }
        else
        {
            $this->db->where('expenses.date BETWEEN ' . 
                $this->db->escape(rawurldecode($inputs['start_date'])) . 
                ' AND ' . 
                $this->db->escape(rawurldecode($inputs['end_date']))
            );
        }
        $this->db->where('expenses.deleted', 0);
        $this->db->group_by('expense_categories.category_name');
        $this->db->order_by('expense_categories.category_name');
        return $this->db->get()->result_array();
    }

    public function getSummaryData(array $inputs)
    {
        $this->db->select('
            SUM(expenses.amount + expenses.tax_amount) AS total_invoiced,
            SUM(expenses.amount_due)                  AS total_due
        ');
        $this->db->from('expenses AS expenses');
        // misma cláusula de fechas…
        if (empty($this->config->item('date_or_time_format')))
        {
            $this->db->where('DATE(expenses.date) BETWEEN ' . 
                $this->db->escape($inputs['start_date']) . 
                ' AND ' . 
                $this->db->escape($inputs['end_date'])
            );
        }
        else
        {
            $this->db->where('expenses.date BETWEEN ' . 
                $this->db->escape(rawurldecode($inputs['start_date'])) . 
                ' AND ' . 
                $this->db->escape(rawurldecode($inputs['end_date']))
            );
        }
        $this->db->where('expenses.deleted', 0);
        return $this->db->get()->row_array();
    }
}
?>
