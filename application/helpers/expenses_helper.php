<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_accounts_payable_summary($accounts_payable)
{
    $CI =& get_instance();
    $table  = '<div id="accounts_payable_summary">';
    $table .= '<div class="summary_row">' 
            . $CI->lang->line('expenses_total_amount') . ': ' 
            . to_currency($accounts_payable['total_amount']) 
            . '</div>';
    $table .= '<div class="summary_row">' 
            . $CI->lang->line('expenses_total_paid') . ': ' 
            . to_currency($accounts_payable['total_paid']) 
            . '</div>';
    $table .= '<div class="summary_row">' 
            . $CI->lang->line('expenses_total_due') . ': ' 
            . to_currency($accounts_payable['total_due']) 
            . '</div>';
    $table .= '</div>';
    return $table;
}
