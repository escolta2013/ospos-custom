<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Sale class
 */
class Billtocollect extends CI_Model
{
	/**
	 * Get sale info
	 */
	public function get_info($sale_id)
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that they are ortogonal to the main query
		// create a temporary table to contain all the payments per sale
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_payments_temp') .
			' (PRIMARY KEY(sale_id), INDEX(sale_id))
			(
			SELECT payments.sale_id AS sale_id,
			IFNULL(SUM(payments.payment_amount), 0) AS sale_payment_amount,
			GROUP_CONCAT(CONCAT(payments.payment_type, " ", (payments.payment_amount - payments.cash_refund)) SEPARATOR ", ") AS payment_type
			FROM ' . $this->db->dbprefix('sales_payments') . ' AS payments
			INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
			ON sales.sale_id = payments.sale_id
			WHERE sales.sale_id = ' . $this->db->escape($sale_id) . '
			GROUP BY sale_id
		)'
	);

		$decimals = totals_decimals();

		$sale_price = 'CASE WHEN sales_items.discount_type = ' . PERCENT . ' THEN sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount / 100) ELSE sales_items.item_unit_price * sales_items.quantity_purchased - sales_items.discount END';
		$tax = 'ROUND(IFNULL(SUM(sales_items_taxes.tax), 0), ' . $decimals . ')';

		if($this->config->item('tax_included'))
		{
			$sale_total = 'ROUND(SUM(' . $sale_price . '),' . $decimals . ')';
			$sale_subtotal = $sale_total . ' - ' . $tax;
		}
		else
		{
			$sale_subtotal = 'ROUND(SUM(' . $sale_price . '),' . $decimals . ')';
			$sale_total = $sale_subtotal . ' + ' . $tax;
		}

		// create a temporary table to contain all the sum of taxes per sale item
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_items_taxes_temp') .
			' (INDEX(sale_id), INDEX(item_id)) ENGINE=MEMORY
			(
			SELECT sales_items_taxes.sale_id AS sale_id,
			sales_items_taxes.item_id AS item_id,
			sales_items_taxes.line AS line,
			SUM(sales_items_taxes.item_tax_amount) AS tax
			FROM ' . $this->db->dbprefix('sales_items_taxes') . ' AS sales_items_taxes
			INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
			ON sales.sale_id = sales_items_taxes.sale_id
			INNER JOIN ' . $this->db->dbprefix('sales_items') . ' AS sales_items
			ON sales_items.sale_id = sales_items_taxes.sale_id AND sales_items.line = sales_items_taxes.line
			WHERE sales.sale_id = ' . $this->db->escape($sale_id) . '
			GROUP BY sale_id, item_id, line
		)'
	);

		$this->db->select('
			sales.sale_id AS sale_id,
			MAX(DATE(sales.sale_time)) AS sale_date,
			MAX(sales.sale_time) AS sale_time,
			MAX(sales.comment) AS comment,
			MAX(sales.sale_status) AS sale_status,
			MAX(sales.sale_type) AS sale_type,
			MAX(sales.invoice_number) AS invoice_number,
			MAX(sales.quote_number) AS quote_number,
			MAX(sales.employee_id) AS employee_id,
			MAX(sales.customer_id) AS customer_id,
			MAX(CONCAT(customer_p.first_name, " ", customer_p.last_name)) AS customer_name,
			MAX(CONCAT(customer_p.geo_lat)) AS geo_lat,
			MAX(CONCAT(customer_p.geo_lon)) AS geo_lon,
			MAX(customer_p.first_name) AS first_name,
			MAX(customer_p.last_name) AS last_name,
			MAX(customer_p.email) AS email,
			MAX(customer_p.comments) AS comments,
			' . "
			IFNULL($sale_total, $sale_subtotal) AS amount_due,
			IFNULL(MAX(payments.sale_payment_amount), 0) AS amount_tendered,
			IFNULL(MAX(payments.sale_payment_amount) - IFNULL($sale_total, $sale_subtotal),0) AS change_due,
			" . '
			MAX(payments.payment_type) AS payment_type,
			sales.exinput6 AS exinput6
			');

		$this->db->from('sales_items AS sales_items');
		$this->db->join('sales AS sales', 'sales_items.sale_id = sales.sale_id', 'inner');
		$this->db->join('people AS customer_p', 'sales.customer_id = customer_p.person_id', 'LEFT');
		$this->db->join('customers AS customer', 'sales.customer_id = customer.person_id', 'LEFT');
		$this->db->join('employees AS employee', 'sales.employee_id = employee.person_id', 'LEFT');
		$this->db->join('sales_payments_temp AS payments', 'sales.sale_id = payments.sale_id', 'LEFT OUTER');
		$this->db->join('sales_items_taxes_temp AS sales_items_taxes',
			'sales_items.sale_id = sales_items_taxes.sale_id AND sales_items.item_id = sales_items_taxes.item_id AND sales_items.line = sales_items_taxes.line',
			'LEFT OUTER');

		$this->db->where('sales.sale_id', $sale_id);

		if(!empty($search))
		{

			$this->db->group_start();
					// customer last name
			$this->db->like('customer.last_name', $search);
					// customer first name
			$this->db->or_like('customer.first_name', $search);
					// customer first and last name
			$this->db->or_like('CONCAT(customer.first_name, " ", customer.last_name)', $search);

			$this->db->or_like('employee.last_name', $search);
					// employee first name
			$this->db->or_like('employee.first_name', $search);
					// employee first and last name
			$this->db->or_like('CONCAT(employee.first_name, " ", employee.last_name)', $search);

            $this->db->group_end();
 

		}


		$this->db->group_by('sale_id');
		$this->db->order_by('sale_time', 'asc');

		return $this->db->get();
	}

	/**
	 * Get number of rows for the takings (sales/manage) view
	 */
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'sales.sale_time', 'desc', TRUE);
	}

	/**
	 * Get the sales data for the takings (sales/manage) view
	 */
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'sales.sale_time', $order = 'desc', $count_only = FALSE)
	{

		$sort = 'sales.customer_id, sales.sale_time';
		// Pick up only non-suspended records and 
		// nuker 12-11-22 filtrar solo si hay abonos
		
		$where = 'payments.payment_type = "'.$this->lang->line('sales_due').'" AND ';  

		if(empty($this->config->item('date_or_time_format')))
		{
			$where .= 'DATE(sales.sale_time) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']);
		}
		else
		{
			$where .= 'sales.sale_time BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date']));
		} 
          
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(DISTINCT sales.sale_id) AS count');
		}else{

			$this->db->select('
			sales.sale_id AS sale_id,
			sales.customer_id AS customer_id,
			sales.employee_id AS employee_id,
			sales.sale_status AS sale_status,
			MAX(DATE_FORMAT(payments.payment_time, "%d-%m-%Y" )) AS sale_date,
			MAX(sales.sale_time) AS sale_time,			 
			SUM(payments.payment_amount) as payment_amounts,
			MAX(sales.invoice_number) AS invoice_number,
			MAX(sales.quote_number) AS quote_number,
			MAX(sales.sale_type) AS sale_type,
			MAX(CONCAT(employee.first_name, " ", employee.last_name)) AS employee_name,  
			MAX(CONCAT(customer.first_name, " ", customer.last_name)) AS customer_name
			');

			}


		$this->db->from('sales AS sales');

		/* $this->db->join('sales_payments_tocredit AS  payments_tocredit', 'payments_tocredit.sale_id = sales.sale_id', 'left');*/

		$this->db->join('sales_payments AS  payments', 'payments.sale_id = sales.sale_id', 'left');
		$this->db->join('people AS customer', 'sales.customer_id = customer.person_id', 'LEFT'); 
		$this->db->join('people AS employee', 'sales.employee_id = employee.person_id', 'LEFT'); 
        $this->db->where('sales.sale_status = ' . COMPLETED ); 
		$this->db->where('sales.status_credito',0);  
		$this->db->where($where);  

		if($filters['employee_id'] != 'all')
		{
			$this->db->where('sales.employee_id', $filters['employee_id']);
		} 

		if($filters['customer_id'] != 'all')
		{
			$this->db->where('sales.customer_id', $filters['customer_id']);
		} 

		
		if(!empty($search))
		{

			$this->db->group_start();
					// customer last name
			$this->db->like('customer.last_name', $search);
					// customer first name
			$this->db->or_like('customer.first_name', $search);
					// customer first and last name
			$this->db->or_like('CONCAT(customer.first_name, " ", customer.last_name)', $search);

			$this->db->or_like('employee.last_name', $search);
					// employee first name
			$this->db->or_like('employee.first_name', $search);
					// employee first and last name
			$this->db->or_like('CONCAT(employee.first_name, " ", employee.last_name)', $search);

            $this->db->group_end();
 

		}

		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}
		
		$this->db->group_by('sales.sale_id');

		// order by sale time by default
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/**
	 * Get the payment summary for the takings (sales/manage) view
	 */

	public function get_payments_tc_summary($search, $filters)
	{
		// get payment summary
		$this->db->select('payment_type, COUNT(payment_amount) AS count, SUM(payment_amount) AS payment_amount');
		$this->db->from('sales AS sales');
		$this->db->join('sales_payments_tocredit', 'sales_payments_tocredit.sale_id = sales.sale_id');		
		$this->db->join('people AS customer_p', 'sales.customer_id = customer_p.person_id', 'LEFT');
		$this->db->join('people AS employee_p', 'sales.employee_id = employee_p.person_id', 'LEFT');
		$this->db->join('customers AS customer', 'sales.customer_id = customer.person_id', 'LEFT');
		$this->db->join('employees AS employee', 'sales.employee_id = employee.person_id', 'LEFT');
		$this->db->where('sales.status_credito',0); 
		$this->db->where('sales.sale_status = ' . COMPLETED ); 
		
		if($filters['employee_id'] != 'all')
		{
			$this->db->where('sales.employee_id', $filters['employee_id']);
		} 

		if($filters['customer_id'] != 'all')
		{
			$this->db->where('sales.customer_id', $filters['customer_id']);
		} 
		
		if(empty($this->config->item('date_or_time_format')))
		{
			$this->db->where('DATE(sales.sale_time) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		}
		else
		{
			$this->db->where('sales.sale_time BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date'])));
		}
 
				$this->db->group_start();
					// customer last name
				$this->db->like('customer_p.last_name', $search);
					// customer first name
				$this->db->or_like('customer_p.first_name', $search);
					// customer first and last name
				$this->db->or_like('CONCAT(customer_p.first_name, " ", customer_p.last_name)', $search);

				$this->db->or_like('employee_p.last_name', $search);
					// customer first name
				$this->db->or_like('employee_p.first_name', $search);
					// customer first and last name
				$this->db->or_like('CONCAT(employee_p.first_name, " ", employee_p.last_name)', $search);

				$this->db->group_end();

		 
		 

	$this->db->group_by('payment_type');

	$payments = $this->db->get()->result_array();

		 

	return $payments;
}

	public function get_payments_summary($search, $filters)
	{
		// get payment summary
		$this->db->select('payment_type, COUNT(payment_amount) AS count, SUM(payment_amount) AS payment_amount');
		$this->db->from('sales AS sales');
		$this->db->join('sales_payments', 'sales_payments.sale_id = sales.sale_id');		
		$this->db->join('people AS customer_p', 'sales.customer_id = customer_p.person_id', 'LEFT');
		$this->db->join('people AS employee_p', 'sales.employee_id = employee_p.person_id', 'LEFT');
		$this->db->join('customers AS customer', 'sales.customer_id = customer.person_id', 'LEFT');
		$this->db->join('employees AS employee', 'sales.employee_id = employee.person_id', 'LEFT');
		$this->db->where('sales.status_credito',0); 
		$this->db->where('sales.sale_status = ' . COMPLETED );
		$this->db->where('sales_payments.payment_type', $this->lang->line('sales_due'));
		
		if($filters['employee_id'] != 'all')
		{
			$this->db->where('sales.employee_id', $filters['employee_id']);
		} 

		if($filters['customer_id'] != 'all')
		{
			$this->db->where('sales.customer_id', $filters['customer_id']);
		} 
		
		if(empty($this->config->item('date_or_time_format')))
		{
			$this->db->where('DATE(sales.sale_time) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));
		}
		else
		{
			$this->db->where('sales.sale_time BETWEEN ' . $this->db->escape(rawurldecode($filters['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($filters['end_date'])));
		}

		if(!empty($search))
		{
			 
				$this->db->group_start();
					// customer last name
				$this->db->like('customer_p.last_name', $search);
					// customer first name
				$this->db->or_like('customer_p.first_name', $search);
					// customer first and last name
				$this->db->or_like('CONCAT(customer_p.first_name, " ", customer_p.last_name)', $search);

				$this->db->or_like('employee_p.last_name', $search);
					// customer first name
				$this->db->or_like('employee_p.first_name', $search);
					// customer first and last name
				$this->db->or_like('CONCAT(employee_p.first_name, " ", employee_p.last_name)', $search);

				$this->db->group_end();
		 
		}

		 

	 

	$this->db->group_by('payment_type');

	$payments = $this->db->get()->result_array();

	

	return $payments;
}

	/**
	 * Gets total of rows
	 */
	public function get_total_rows()
	{
		$this->db->from('sales');

		return $this->db->count_all_results();
	}

	/**
	 * Gets search suggestions
	 */
	public function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		if(!$this->is_valid_receipt($search))
		{
			$this->db->distinct();
			$this->db->select('first_name, last_name');
			$this->db->from('sales');
			$this->db->join('people', 'people.person_id = sales.customer_id');
			$this->db->like('last_name', $search);
			$this->db->or_like('first_name', $search);
			$this->db->or_like('CONCAT(first_name, " ", last_name)', $search);
			$this->db->or_like('company_name', $search);
			$this->db->order_by('last_name', 'asc');

			foreach($this->db->get()->result_array() as $result)
			{
				$suggestions[] = array('label' => $result['first_name'] . ' ' . $result['last_name']);
			}
		}
		else
		{
			$suggestions[] = array('label' => $search);
		}

		return $suggestions;
	}

	/**
	 * Gets total of invoice rows
	 */
	public function get_invoice_count()
	{
		$this->db->from('sales');
		$this->db->where('invoice_number IS NOT NULL');

		return $this->db->count_all_results();
	}

	/**
	 * Gets sale by invoice number
	 */
	public function get_sale_by_invoice_number($invoice_number)
	{
		$this->db->from('sales');
		$this->db->where('invoice_number', $invoice_number);

		return $this->db->get();
	}

	/**
	 * Gets invoice number by year
	 */
	public function get_invoice_number_for_year($year = '', $start_from = 0)
	{
		$year = $year == '' ? date('Y') : $year;
		$this->db->select('COUNT( 1 ) AS invoice_number_year');
		$this->db->from('sales');
		$this->db->where('DATE_FORMAT(sale_time, "%Y" ) = ', $year);
		$this->db->where('invoice_number IS NOT NULL');
		$result = $this->db->get()->row_array();

		return ($start_from + $result['invoice_number_year']);
	}

	/**
	 * Checks if valid receipt
	 */
	public function is_valid_receipt(&$receipt_sale_id)
	{
		if(!empty($receipt_sale_id))
		{
			//POS #
			$pieces = explode(' ', $receipt_sale_id);

			if(count($pieces) == 2 && preg_match('/(POS)/i', $pieces[0]))
			{
				return $this->exists($pieces[1]);
			}
			elseif($this->config->item('invoice_enable') == TRUE)
			{
				$sale_info = $this->get_sale_by_invoice_number($receipt_sale_id);
				if($sale_info->num_rows() > 0)
				{
					$receipt_sale_id = 'POS ' . $sale_info->row()->sale_id;

					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Checks if sale exists
	 */
	public function exists($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return ($this->db->get()->num_rows()==1);
	}

	/**
	 * Update sale
	 */
	public function update($sale_id, $sale_data, $payments)
	{
		$this->db->where('sale_id', $sale_id);
		$success = $this->db->update('sales', $sale_data);

		// touch payment only if update sale is successful and there is a payments object otherwise the result would be to delete all the payments associated to the sale
		if($success && !empty($payments))
		{
			//Run these queries as a transaction, we want to make sure we do all or nothing
			$this->db->trans_start();

			// add new payments
			foreach($payments as $payment)
			{
				$payment_id = $payment['payment_id'];
				$payment_type = $payment['payment_type'];
				$payment_amount = $payment['payment_amount'];
				$cash_refund = $payment['cash_refund'];
				$employee_id = $payment['employee_id'];

				if ($payment['payment_type'] == currency_m1()) {
					
					$sales_payments_data = array(
						'sale_id'        => $sale_id,
						'payment_type'   => $payment['payment_type'],
						'payment_amount' => $payment['payment_amount'],
						'cash_refund' => $cash_refund,
						'employee_id' => $employee_id,
						'M1'             => operators()== 1 ? $payment['payment_amount'] / value_m1() : $payment['payment_amount'] * value_m1(),
						'T1'             => value_m1(),
					);

				} else if($payment_id == -1 && $payment_amount != 0)
				{
					// Add a new payment transaction
					$sales_payments_data = array(
						'sale_id' => $sale_id,
						'payment_type' => $payment_type,
						'payment_amount' => $payment_amount,
						'cash_refund' => $cash_refund,
						'employee_id' => $employee_id,
						'M1'             => 0,
						'T1'             => 0,
					);
					$success = $this->db->insert('sales_payments', $sales_payments_data);
				}
				elseif($payment_id != -1)
				{
					if($payment_amount != 0)
					{
						// Update existing payment transactions (payment_type only)
						$sales_payments_data = array(
							'payment_type' => $payment_type,
							'payment_amount' => $payment_amount,
							'cash_refund' => $cash_refund,
							'employee_id' => $employee_id,
							'M1'             => 0,
							'T1'             => 0,                        
						);
						$this->db->where('payment_id', $payment_id);
						$success = $this->db->update('sales_payments', $sales_payments_data);
					}
					else
					{
						// Remove existing payment transactions with a payment amount of zero
						$success = $this->db->delete('sales_payments', array('payment_id' => $payment_id));
					}
				}
			}

			$this->db->trans_complete();

			$success &= $this->db->trans_status();
		}

		return $success;
	}

	/**
	 * Save the sale information after the sales is complete but before the final document is printed
	 * The sales_taxes variable needs to be initialized to an empty array before calling
	 */
	public function save($sale_id, &$sale_status, &$items, $customer_id, $employee_id, $comment, $invoice_number,
		/*vf.*/						$work_order_number, $quote_number, $sale_type, $payments, $dinner_table, &$sales_taxes, &$sale_extrainputs = null)
	{
		if($sale_id != -1)
		{
			$this->clear_suspended_sale_detail($sale_id);
		}

		$tax_decimals = tax_decimals();

		if(count($items) == 0)
		{
			return -1;
		}

		$sales_data = array(
			'sale_time'			=> date('Y-m-d H:i:s'),
			'customer_id'		=> $this->Customer->exists($customer_id) ? $customer_id : NULL,
			'employee_id'		=> $employee_id,
			'comment'			=> $comment,
			'sale_status'		=> $sale_status,
			'invoice_number'	=> $invoice_number,
			'quote_number'		=> $quote_number,
			'work_order_number'	=> $work_order_number,
			'dinner_table_id'	=> $dinner_table,
			'sale_status'		=> $sale_status,
			'sale_type'			=> $sale_type
		);
		/*vf*/
		// sale_extrainput implementation, soo para extinput 6 due date
		if(is_array($sale_extrainputs))
		{
			$sales_data['exinput6'] = $sale_extrainputs['sale_extrainput6'];
		}
		else if($sale_extrainputs !== null)
		{
			$sales_data['exinput6'] = $sale_extrainputs;
		}
		/*/vf*/

		// Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		if($sale_id == -1)
		{
			$this->db->insert('sales', $sales_data);
			$sale_id = $this->db->insert_id();
		}
		else
		{
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sales', $sales_data);
		}
		$total_amount = 0;
		$total_amount_used = 0;
		foreach($payments as $payment_id=>$payment)
		{
			if(!empty(strstr($payment['payment_type'], $this->lang->line('sales_giftcard'))))
			{
				// We have a gift card and we have to deduct the used value from the total value of the card.
				$splitpayment = explode( ':', $payment['payment_type'] );
				$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $splitpayment[1] );
				$this->Giftcard->update_giftcard_value( $splitpayment[1], $cur_giftcard_value - $payment['payment_amount'] );
			}
			elseif(!empty(strstr($payment['payment_type'], $this->lang->line('sales_rewards'))))
			{
				$cur_rewards_value = $this->Customer->get_info($customer_id)->points;
				$this->Customer->update_reward_points_value($customer_id, $cur_rewards_value - $payment['payment_amount'] );
				$total_amount_used = floatval($total_amount_used) + floatval($payment['payment_amount']);
			}
			if ($payment['payment_type'] == currency_m1()) {
				
				$sales_payments_data = array(
					'sale_id'        => $sale_id,
					'payment_type'   => $payment['payment_type'],
					'payment_amount' => $payment['payment_amount'],
					'cash_refund'    => $payment['cash_refund'],
					'employee_id'	 => $employee_id,
					'M1'             => operators()== 1 ? $payment['payment_amount'] / value_m1() : $payment['payment_amount'] * value_m1(),
					'T1'             => value_m1(),
				);

			} else {
				$sales_payments_data = array(
					'sale_id'		 => $sale_id,
					'payment_type'	 => $payment['payment_type'],
					'payment_amount' => $payment['payment_amount'],
					'cash_refund'    => $payment['cash_refund'],
					'employee_id'	 => $employee_id,
					'M1'             => 0,
					'T1'             => 0,
				);
			}
			

			$this->db->insert('sales_payments', $sales_payments_data);

			$total_amount = floatval($total_amount) + floatval($payment['payment_amount']);
		}

		$this->save_customer_rewards($customer_id, $sale_id, $total_amount, $total_amount_used);

		$customer = $this->Customer->get_info($customer_id);

		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			if($item['price'] == 0.00)
			{
				$item['discount'] = 0.00;
			}

			$sales_items_data = array(
				'sale_id'			=> $sale_id,
				'item_id'			=> $item['item_id'],
				/*kit*/			'kit_id'			=> $item['kit_id'],
				'kit_quantity'		=> $item['kit_temp'],	/*/kit*/
				'line'				=> $item['line'],
				'description'		=> character_limiter($item['description'], 255),
				'serialnumber'		=> character_limiter($item['serialnumber'], 30),
				'quantity_purchased'=> $item['quantity'],
				'discount'			=> $item['discount'],
				'discount_type'		=> $item['discount_type'],
				'item_cost_price'	=> $item['cost_price'],
				'item_unit_price'	=> $item['price'],
				'item_location'		=> $item['item_location'],
				'print_option'		=> $item['print_option']
			);

			$this->db->insert('sales_items', $sales_items_data);

			if($cur_item_info->stock_type == HAS_STOCK && $sale_status == COMPLETED)
			{
				// Update stock quantity if item type is a standard stock item and the sale is a standard sale
				$item_quantity = $this->Item_quantity->get_item_quantity($item['item_id'], $item['item_location']);
				$this->Item_quantity->save(array('quantity'	=> $item_quantity->quantity - $item['quantity'],
					'item_id'		=> $item['item_id'],
					'location_id'	=> $item['item_location']), $item['item_id'], $item['item_location']);

				// if an items was deleted but later returned it's restored with this rule

				if($item['quantity'] < 0)
				{
					$this->Item->undelete($item['item_id']);
				}

				// Inventory Count Details
				// Get customer info - first and last name			
				/*info-id venta cliente*/
				$customer_info = $this->Customer->get_info($customer_id);
				if($customer_id > 0)
				{
					$data['customer'] = $customer_info->first_name . ' ' . $customer_info->last_name;
					$sale_remarks = 'POS '.$sale_id.' - '.$this->lang->line('customers_customer').': '.$data['customer'];
				}
				else
				{
					$sale_remarks = 'POS '.$sale_id;	
				}
				$inv_data = array(
					'trans_date'		=> date('Y-m-d H:i:s'),
					'trans_items'		=> $item['item_id'],
					'trans_user'		=> $employee_id,
					'trans_location'	=> $item['item_location'],
					'trans_comment'		=> $sale_remarks,
					'trans_inventory'	=> -$item['quantity']
				);
				$this->Inventory->insert($inv_data);
			}

			$this->Attribute->copy_attribute_links($item['item_id'], 'sale_id', $sale_id);
		}

		if($customer_id == -1 || $customer->taxable)
		{
			$this->save_sales_tax($sale_id, $sales_taxes[0]);
			$this->save_sales_items_taxes($sale_id, $sales_taxes[1]);
		}

		if($this->config->item('dinner_table_enable') == TRUE)
		{
			if($sale_status == COMPLETED)
			{
				$this->Dinner_table->release($dinner_table);
			}
			else
			{
				$this->Dinner_table->occupy($dinner_table);
			}
		}

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE)
		{
			return -1;
		}

		return $sale_id;
	}

	/**
	 * Saves sale tax
	 */
	public function save_sales_tax($sale_id, $sales_taxes)
	{
		foreach($sales_taxes as $line=>$sales_tax)
		{
			$sales_tax['sale_id'] = $sale_id;
			$this->db->insert('sales_taxes', $sales_tax);
		}
	}

	/**
	 * Apply customer sales tax if the customer sales tax is enabledl
	 * The original tax is still supported if the user configures it,
	 * but it won't make sense unless it's used exclusively for the purpose
	 * of VAT tax which becomes a price component.  VAT taxes must still be reported
	 * as a separate tax entry on the invoice.
	 */
	public function save_sales_items_taxes($sale_id, $sales_item_taxes)
	{
		foreach($sales_item_taxes as $line => $tax_item)
		{
			$sales_items_taxes = array(
				'sale_id' => $sale_id,
				'item_id' => $tax_item['item_id'],
				'line' => $tax_item['line'],
				'name' => $tax_item['name'],
				'percent' => $tax_item['percent'],
				'tax_type' => $tax_item['tax_type'],
				'rounding_code' => $tax_item['rounding_code'],
				'cascade_sequence' => $tax_item['cascade_sequence'],
				'item_tax_amount' => $tax_item['item_tax_amount'],
				'sales_tax_code_id' => $tax_item['sales_tax_code_id'],
				'tax_category_id' => $tax_item['tax_category_id'],
				'jurisdiction_id' => $tax_item['jurisdiction_id'],
				'tax_category_id' => $tax_item['tax_category_id']
			);

			$this->db->insert('sales_items_taxes', $sales_items_taxes);
		}
	}

	/**
	 * Return the taxes that were charged
	 */
	public function get_sales_taxes($sale_id)
	{
		$this->db->from('sales_taxes');
		$this->db->where('sale_id', $sale_id);
		$this->db->order_by('print_sequence', 'asc');

		$query = $this->db->get();

		return $query->result_array();
	}

	/**
	 * Deletes list of sales
	 */
	public function delete_list($sale_ids, $employee_id, $update_inventory = TRUE)
	{
		$result = TRUE;

		foreach($sale_ids as $sale_id)
		{
			$result &= $this->delete($sale_id, $employee_id, $update_inventory);
		}

		return $result;
	}

	/**
	 * Restores list of sales
	 */
	public function restore_list($sale_ids, $employee_id, $update_inventory = TRUE)
	{
		foreach($sale_ids as $sale_id)
		{
			$this->update_sale_status($sale_id, SUSPENDED);
		}

		return TRUE;
	}

	/**
	 * Delete sale.  Hard deletes are not supported for sales transactions.
	 * When a sale is "deleted" it is simply changed to a status of canceled.
	 * However, if applicable the inventory still needs to be updated
	 */
	public function delete($sale_id, $employee_id, $update_inventory = TRUE)
	{
		// start a transaction to assure data integrity
		$this->db->trans_start();

		$sale_status = $this->get_sale_status($sale_id);

		if($update_inventory && $sale_status == COMPLETED)
		{
			// defect, not all item deletions will be undone??
			// get array with all the items involved in the sale to update the inventory tracking
			$items = $this->get_sale_items($sale_id)->result_array();
			foreach($items as $item)
			{
				$cur_item_info = $this->Item->get_info($item['item_id']);

				if($cur_item_info->stock_type == HAS_STOCK)
				{
					// create query to update inventory tracking
					$inv_data = array(
						'trans_date' => date('Y-m-d H:i:s'),
						'trans_items' => $item['item_id'],
						'trans_user' => $employee_id,
						'trans_comment' => 'Deleting sale ' . $sale_id,
						'trans_location' => $item['item_location'],
						'trans_inventory' => $item['quantity_purchased']
					);
					// update inventory
					$this->Inventory->insert($inv_data);

					// update quantities
					$this->Item_quantity->change_quantity($item['item_id'], $item['item_location'], $item['quantity_purchased']);
				}
			}
		}

		$this->update_sale_status($sale_id, CANCELED);

		// execute transaction
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	/**
	 * Gets sale item
	 */
	public function get_sale_items($sale_id)
	{
		$this->db->from('sales_items');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get();
	}

	/**
	 * Used by the invoice and receipt programs
	 */
	public function get_sale_items_ordered($sale_id)
	{
		$this->db->select('
			sales_items.sale_id,
			sales_items.item_id,
			sales_items.kit_id,
			sales_items.kit_quantity,
			sales_items.description,
			serialnumber,
			line,
			quantity_purchased,
			item_cost_price,
			item_unit_price,
			discount,
			discount_type,
			item_location,
			print_option,
			' . $this->Item->get_item_name('name') . ',
			category,
			item_type,
			stock_type');
		$this->db->from('sales_items AS sales_items');
		$this->db->join('items AS items', 'sales_items.item_id = items.item_id');
		$this->db->where('sales_items.sale_id', $sale_id);

		// Entry sequence (this will render kits in the expected sequence)
		if($this->config->item('line_sequence') == '0')
		{
			$this->db->order_by('line', 'asc');
		}
		// Group by Stock Type (nonstock first - type 1, stock next - type 0)
		elseif($this->config->item('line_sequence') == '1')
		{
			$this->db->order_by('stock_type', 'desc');
			$this->db->order_by('sales_items.description', 'asc');
			$this->db->order_by('items.name', 'asc');
			$this->db->order_by('items.qty_per_pack', 'asc');
		}
		// Group by Item Category
		elseif($this->config->item('line_sequence') == '2')
		{
			$this->db->order_by('category', 'asc');
			$this->db->order_by('sales_items.description', 'asc');
			$this->db->order_by('items.name', 'asc');
			$this->db->order_by('items.qty_per_pack', 'asc');
		}
		// Group by entry sequence in descending sequence (the Standard)
		else
		{
			$this->db->order_by('line', 'desc');
		}

		return $this->db->get();
	}

	/**
	 * Gets sale payments
	 */
	public function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get();
	}

	/**
	 * Gets sale payment options
	 */
	public function get_payment_options($giftcard = TRUE, $reward_points = FALSE)
	{
		$payments = get_payment_options();

		if($giftcard == TRUE)
		{
			$payments[$this->lang->line('sales_giftcard')] = $this->lang->line('sales_giftcard');
		}

		if($reward_points == TRUE)
		{
			$payments[$this->lang->line('sales_rewards')] = $this->lang->line('sales_rewards');
		}

		if($this->sale_lib->get_mode() == 'sale_work_order')
		{
			$payments[$this->lang->line('sales_cash_deposit')] = $this->lang->line('sales_cash_deposit');
			$payments[$this->lang->line('sales_credit_deposit')] = $this->lang->line('sales_credit_deposit');
		}
	/**/$payments[$this->lang->line('sales_due')] = $this->lang->line('sales_due');/*/*/ // ESTE ES LA OPCION DE VENTAS A CREDITO EN SALES
	return $payments;
}

	/**
	 * Gets sale customer name
	 */
	public function get_customer($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}

	/**
	 * Gets sale employee name
	 */
	public function get_employee($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->Employee->get_info($this->db->get()->row()->employee_id);
	}

	/**
	 * Checks if quote number exists
	 */
	// TODO change to use new quote_number field
	public function check_quote_number_exists($quote_number, $sale_id = '')
	{
		$this->db->from('sales');
		$this->db->where('quote_number', $quote_number);
		if(!empty($sale_id))
		{
			$this->db->where('sale_id !=', $sale_id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	/**
	 * Checks if invoice number exists
	 */
	public function check_invoice_number_exists($invoice_number, $sale_id = '')
	{
		$this->db->from('sales');
		$this->db->where('invoice_number', $invoice_number);
		if(!empty($sale_id))
		{
			$this->db->where('sale_id !=', $sale_id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	/**
	 * Checks if work order number exists
	 */
	public function check_work_order_number_exists($work_order_number, $sale_id = '')
	{
		$this->db->from('sales');
		$this->db->where('invoice_number', $work_order_number);
		if(!empty($sale_id))
		{
			$this->db->where('sale_id !=', $sale_id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	/**
	 * Gets Giftcard value
	 */
	public function get_giftcard_value($giftcardNumber)
	{
		if(!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($giftcardNumber)))
		{
			return 0;
		}

		$this->db->from('giftcards');
		$this->db->where('giftcard_number', $giftcardNumber);

		return $this->db->get()->row()->value;
	}

	/**
	 * Creates sales temporary dimentional table
	 * We create a temp table that allows us to do easy report/sales queries
	 */
	public function create_temp_table(array $inputs)
	{
		if(empty($inputs['sale_id']))
		{
			if(empty($this->config->item('date_or_time_format')))
			{
				$where = 'DATE(sales.sale_time) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']);
			}
			else
			{
				$where = 'sales.sale_time BETWEEN ' . $this->db->escape(rawurldecode($inputs['start_date'])) . ' AND ' . $this->db->escape(rawurldecode($inputs['end_date']));
			}
		}
		else
		{
			$where = 'sales.sale_id = ' . $this->db->escape($inputs['sale_id']);
		}

		$decimals = totals_decimals();

		$sale_price = 'CASE WHEN sales_items.discount_type = ' . PERCENT . ' THEN sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount / 100) ELSE sales_items.item_unit_price * sales_items.quantity_purchased - sales_items.discount END';
		$sale_cost = 'SUM(sales_items.item_cost_price * sales_items.quantity_purchased)';
		$tax = 'IFNULL(SUM(sales_items_taxes.tax), 0)';

		if($this->config->item('tax_included'))
		{
			$sale_total = 'ROUND(SUM(' . $sale_price . '), ' . $decimals . ')';
			$sale_subtotal = $sale_total . ' - ' . $tax;
		}
		else
		{
			$sale_subtotal = 'ROUND(SUM(' . $sale_price . '), ' . $decimals . ')';
			$sale_total = $sale_subtotal . ' + ' . $tax;
		}

		// create a temporary table to contain all the sum of taxes per sale item
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_items_taxes_temp') .
			' (INDEX(sale_id), INDEX(item_id)) ENGINE=MEMORY
			(
			SELECT sales_items_taxes.sale_id AS sale_id,
			sales_items_taxes.item_id AS item_id,
			sales_items_taxes.line AS line,
			SUM(sales_items_taxes.item_tax_amount) AS tax
			FROM ' . $this->db->dbprefix('sales_items_taxes') . ' AS sales_items_taxes
			INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
			ON sales.sale_id = sales_items_taxes.sale_id
			INNER JOIN ' . $this->db->dbprefix('sales_items') . ' AS sales_items
			ON sales_items.sale_id = sales_items_taxes.sale_id AND sales_items.line = sales_items_taxes.line
			WHERE ' . $where . '
			GROUP BY sale_id, item_id, line
		)'
	);

		// create a temporary table to contain all the payment types and amount
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_payments_temp') .
			' (PRIMARY KEY(sale_id), INDEX(sale_id))
			(
			SELECT payments.sale_id AS sale_id,
			IFNULL(SUM(payments.payment_amount), 0) AS sale_payment_amount,
			GROUP_CONCAT(CONCAT(payments.payment_type, " ", (payments.payment_amount - payments.cash_refund)) SEPARATOR ", ") AS payment_type
			FROM ' . $this->db->dbprefix('sales_payments') . ' AS payments
			INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
			ON sales.sale_id = payments.sale_id
			WHERE ' . $where . '
			GROUP BY payments.sale_id
		)'
	);

		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_items_temp') .
			' (INDEX(sale_date), INDEX(sale_time), INDEX(sale_id))
			(
			SELECT
			MAX(DATE(sales.sale_time)) AS sale_date,
			MAX(sales.sale_time) AS sale_time,
			sales.sale_id AS sale_id,
			MAX(sales.sale_status) AS sale_status,
			MAX(sales.sale_type) AS sale_type,
			MAX(sales.comment) AS comment,
			MAX(sales.invoice_number) AS invoice_number,
			MAX(sales.quote_number) AS quote_number,
			MAX(sales.customer_id) AS customer_id,
			MAX(CONCAT(customer_p.first_name, " ", customer_p.last_name)) AS customer_name, 
			MAX(customer_p.first_name) AS customer_first_name,
			MAX(customer_p.last_name) AS customer_last_name,
			MAX(customer_p.email) AS customer_email,
			MAX(customer_p.comments) AS customer_comments,
			MAX(customer.company_name) AS customer_company_name,
			MAX(sales.employee_id) AS employee_id,
			MAX(CONCAT(employee.first_name, " ", employee.last_name)) AS employee_name,
			MAX(employee_p.first_name) AS employee_first_name,
			MAX(employee_p.last_name) AS employee_last_name,
			items.item_id AS item_id,
			MAX(' . $this->Item->get_item_name() . ') AS name,
			MAX(items.item_number) AS item_number,
			MAX(items.category) AS category,
			MAX(items.supplier_id) AS supplier_id,
			MAX(sales_items.quantity_purchased) AS quantity_purchased,
			MAX(sales_items.item_cost_price) AS item_cost_price,
			MAX(sales_items.item_unit_price) AS item_unit_price,
			MAX(sales_items.discount) AS discount,
			sales_items.discount_type AS discount_type,
			sales_items.line AS line,
			MAX(sales_items.serialnumber) AS serialnumber,
			MAX(sales_items.item_location) AS item_location,
			MAX(sales_items.description) AS description,
			MAX(payments.payment_type) AS payment_type,
			MAX(payments.sale_payment_amount) AS sale_payment_amount,
			' . "
			IFNULL($sale_subtotal, $sale_total) AS subtotal,
			$tax AS tax,
			IFNULL($sale_total, $sale_subtotal) AS total,
			$sale_cost AS cost,
			(IFNULL($sale_subtotal, $sale_total) - $sale_cost) AS profit
			" . '
			FROM ' . $this->db->dbprefix('sales_items') . ' AS sales_items
			INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
			ON sales_items.sale_id = sales.sale_id
			INNER JOIN ' . $this->db->dbprefix('items') . ' AS items
			ON sales_items.item_id = items.item_id
			LEFT OUTER JOIN ' . $this->db->dbprefix('sales_payments_temp') . ' AS payments
			ON sales_items.sale_id = payments.sale_id
			LEFT OUTER JOIN ' . $this->db->dbprefix('suppliers') . ' AS supplier
			ON items.supplier_id = supplier.person_id
			LEFT OUTER JOIN ' . $this->db->dbprefix('people') . ' AS customer_p
			ON sales.customer_id = customer_p.person_id
			LEFT OUTER JOIN ' . $this->db->dbprefix('customers') . ' AS customer
			ON sales.customer_id = customer.person_id

		    LEFT OUTER JOIN ' . $this->db->dbprefix('employees') . ' AS employee
			ON sales.employee_id = employee.person_id

			LEFT OUTER JOIN ' . $this->db->dbprefix('people') . ' AS employee
			ON sales.employee_id = employee.person_id
			LEFT OUTER JOIN ' . $this->db->dbprefix('sales_items_taxes_temp') . ' AS sales_items_taxes
			ON sales_items.sale_id = sales_items_taxes.sale_id AND sales_items.item_id = sales_items_taxes.item_id AND sales_items.line = sales_items_taxes.line
			WHERE ' . $where . '
			GROUP BY sale_id, item_id, line
		)'
	);

		// drop the temporary table to contain memory consumption as it's no longer required
		$this->db->query('DROP TEMPORARY TABLE IF EXISTS ' . $this->db->dbprefix('sales_payments_temp'));
		$this->db->query('DROP TEMPORARY TABLE IF EXISTS ' . $this->db->dbprefix('sales_items_taxes_temp'));
	}

	/**
	 * Retrieves all sales that are in a suspended state
	 */
	public function get_all_suspended($customer_id = NULL)
	{
		if($customer_id == -1)
		{
			$query = $this->db->query("SELECT sale_id, case when sale_type = '".SALE_TYPE_QUOTE."' THEN quote_number WHEN sale_type = '".SALE_TYPE_WORK_ORDER."' THEN work_order_number else sale_id end as doc_id, sale_id as suspended_sale_id, sale_status, sale_time, dinner_table_id, customer_id, employee_id, comment FROM "
				. $this->db->dbprefix('sales') . ' where sale_status = ' . SUSPENDED);
		}
		else
		{
			$query = $this->db->query("SELECT sale_id, case when sale_type = '".SALE_TYPE_QUOTE."' THEN quote_number WHEN sale_type = '".SALE_TYPE_WORK_ORDER."' THEN work_order_number else sale_id end as doc_id, sale_status, sale_time, dinner_table_id, customer_id, employee_id, comment FROM "
				. $this->db->dbprefix('sales') . ' where sale_status = '. SUSPENDED .' AND customer_id = ' . $customer_id);
		}

		return $query->result_array();
	}

	/**
	 * Gets the dinner table for the selected sale
	 */
	public function get_dinner_table($sale_id)
	{
		if($sale_id == -1)
		{
			return NULL;
		}

		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get()->row()->dinner_table_id;
	}

	/**
	 * Gets the sale type for the selected sale
	 */
	public function get_sale_type($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get()->row()->sale_type;
	}

	/**
	 * Gets the sale status for the selected sale
	 */
	public function get_sale_status($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get()->row()->sale_status;
	}
	
	/**
	 * Gets the quote_number for the selected sale
	 */
	public function get_quote_number($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		$row = $this->db->get()->row();

		if($row != NULL)
		{
			return $row->quote_number;
		}

		return NULL;
	}

	/**
	 * Gets the work order number for the selected sale
	 */
	public function get_work_order_number($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		$row = $this->db->get()->row();

		if($row != NULL)
		{
			return $row->work_order_number;
		}

		return NULL;
	}

	/**
	 * Gets the quote_number for the selected sale
	 */
	public function get_comment($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		$row = $this->db->get()->row();

		if($row != NULL)
		{
			return $row->comment;
		}

		return NULL;
	}

	/**
	 * Gets total of suspended invoices rows
	 */
	public function get_suspended_invoice_count()
	{
		$this->db->from('sales');
		$this->db->where('invoice_number IS NOT NULL');
		$this->db->where('sale_status', SUSPENDED);

		return $this->db->count_all_results();
	}

	/**
	 * Removes a selected sale from the sales table.
	 * This function should only be called for suspended sales that are being restored to the current cart
	 */
	public function delete_suspended_sale($sale_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		if($this->config->item('dinner_table_enable') == TRUE)
		{
			$dinner_table = $this->get_dinner_table($sale_id);
			$this->Dinner_table->release($dinner_table);
		}

		$this->update_sale_status($sale_id, CANCELED);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	/**
	 * This clears the sales detail for a given sale_id before the detail is resaved.
	 * This allows us to reuse the same sale_id
	 */
	public function clear_suspended_sale_detail($sale_id)
	{
		$this->db->trans_start();


		if($this->config->item('dinner_table_enable') == TRUE)
		{
			$dinner_table = $this->get_dinner_table($sale_id);
			$this->Dinner_table->release($dinner_table);
		}

		$this->db->delete('sales_payments', array('sale_id' => $sale_id));
		$this->db->delete('sales_items_taxes', array('sale_id' => $sale_id));
		$this->db->delete('sales_items', array('sale_id' => $sale_id));
		$this->db->delete('sales_taxes', array('sale_id' => $sale_id));

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	/**
	 * Gets suspended sale info
	 */
	public function get_suspended_sale_info($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);
		$this->db->join('people', 'people.person_id = sales.customer_id', 'LEFT');
		$this->db-where('sale_status', SUSPENDED);

		return $this->db->get();
	}

	/**
	 * @param $customer_id
	 * @param $sale_id
	 * @param $total_amount
	 * @param $total_amount_used
	 */
	private function save_customer_rewards($customer_id, $sale_id, $total_amount, $total_amount_used)
	{
		if(!empty($customer_id) && $this->config->item('customer_reward_enable') == TRUE)
		{
			$package_id = $this->Customer->get_info($customer_id)->package_id;

			if(!empty($package_id))
			{
				$points_percent = $this->Customer_rewards->get_points_percent($package_id);
				$points = $this->Customer->get_info($customer_id)->points;
				$points = ($points == NULL ? 0 : $points);
				$points_percent = ($points_percent == NULL ? 0 : $points_percent);
				$total_amount_earned = ($total_amount * $points_percent / 100);
				$points = $points + $total_amount_earned;
				$this->Customer->update_reward_points_value($customer_id, $points);
				$rewards_data = array('sale_id' => $sale_id, 'earned' => $total_amount_earned, 'used' => $total_amount_used);
				$this->Rewards->save($rewards_data);
			}
		}
	}
	
	/**
	 * salva un pago a la vez de credito es decir abona
	 */
	public function save_payment_tocredit($sale_id, $amount, $id = null)
	{
		if(strpos($id, 'F20') === FALSE)
			$id = 'F'.date('YmdHis');

		$payment_tocredit = array(
			'sale_id'			=> $sale_id,
			'payment_tocredit'	=> $id,
			'payment_amount'	=> $amount
		);

		if($amount >0)
			$this->db->insert('sales_payments_tocredit', $payment_tocredit);
	}
	/**/
	/**
	 * obtiene arreglo de todos los pagos parciales de una venta a credito
	 */
	public function get_payments_tocredit($sale_id)
	{
		$this->db->select('sale_id, payment_tocredit, payment_amount');
		$this->db->from('sales_payments_tocredit');
		$this->db->where('sale_id', $sale_id);

		$payments_tocredits = $this->db->get()->result_array();

		if(count($payments_tocredits) < 1)
			$payments_tocredits[$sale_id] = 0;

		return $payments_tocredits;
	}

	/**
	 * obtiene cuanto lleva total pagado del credito
	 */
	public function get_payment_tocredit($sale_id)
	{
		$this->db->select('IFNULL(SUM(payment_amount), 0) as payment_amount');
		$this->db->from('sales_payments_tocredit');
		$this->db->where('sale_id', $sale_id);

		foreach($this->db->get()->result_array() as $result)
		{
			$payments_tocredits = $result['payment_amount'];
		}

		return $payments_tocredits + 0;
	}


	/**
	 * crea la tabla de pagos temporales pero adiciona cuanto lleva abonado
	 */
	public function create_temporaly_table_payments($where = NULL)
	{
		/* primero la tabla de ospos tal como la hacen.. sin el abono */
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_payments_temp_old') .
			' (PRIMARY KEY(sale_id), INDEX(sale_id))
			(
			SELECT payments.sale_id AS sale_id,
			IFNULL(SUM(payments.payment_amount), 0) AS sale_payment_amount,
			GROUP_CONCAT(CONCAT(payments.payment_type, ": ", (payments.payment_amount - payments.cash_refund )) SEPARATOR ", ") AS payment_type, GROUP_CONCAT(CONCAT(payments.payment_type, " ", payments.M1) SEPARATOR ", ") AS M1
			FROM ' . $this->db->dbprefix('sales_payments') . ' AS payments
			INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
			ON sales.sale_id = payments.sale_id
			WHERE ' . $where . '
			GROUP BY sale_id
		)'
	);
		
		/* columna de pagos es un dato cruzado, complicado SQL, mas facil y rapido de hacer es con selects es el peor metodo pero el mas rapido de codificar y facil de hacer */
		$newtemptablequery = "";
		$query = $this->db->query('SELECT * FROM ' . $this->db->dbprefix('sales_payments_temp_old') .'');
		$query_results = $query->result_array();
		foreach ( $query_results as $row)
		{
			$abonos = '';
			$sir = $row['sale_id'];
			$spar = $row['sale_payment_amount'];

			$array_pay_type=explode(": ",$row['payment_type']);

			$ptr = $array_pay_type[0].": ".to_currency($array_pay_type[1]);
			$credit_rest = '';

			if( strpos($row['payment_type'],$this->lang->line('sales_due')) !== FALSE)
			{
				$query_abono = $this->db->query('
					SELECT SUM(payment_amount) as addeds 
					FROM ' . $this->db->dbprefix('sales_payments_tocredit') . '
					WHERE sale_id='.$sir); // busco el total de abono de esta deuda
				$rowadd = $query_abono->row_array();
				if (isset($rowadd)) // revisamos no exista error
					if ( ! empty($rowadd['addeds']) ){ // pero que no este vacio en caso de exito
						$abonos = ', '.$this->lang->line('sales_amount_tendered').': '.to_currency($rowadd['addeds']);
						$credit_rest = ', '.$this->lang->line('reports_trans_due').': '.to_currency(($row['sale_payment_amount']-$rowadd['addeds']));
					}
				}
				
				$newtemptablequery = $newtemptablequery . "
				SELECT ".$sir." as sale_id, 
				".$spar." as sale_payment_amount, 
				'".$ptr.$abonos.$credit_rest."' as payment_type
				UNION 
			"; // creo un nuevo query para crear lso resultados con el abono de cruze
		}
		$newtemptablequery =  preg_replace('/\W\w+\s*(\W*)$/', '$1', $newtemptablequery);

		/* ahora rehacer la tabla de ospos original pero con los abonos anexados */
		$this->db->query('CREATE TEMPORARY TABLE 
			IF NOT EXISTS ' . $this->db->dbprefix('sales_payments_temp') .' (PRIMARY KEY(sale_id), INDEX(sale_id))
			AS (
			SELECT * FROM (
			'.$newtemptablequery.' ) as TABLENEW
		)'
	);

		$this->db->query('DROP TABLE IF EXISTS ' . $this->db->dbprefix('sales_payments_temp_old') );

	}
	
	public function get_sale_totalcredits($person_id){
		$totalcredits = 0;
		
		$this->db->select(
			'SUM(sales_payments.payment_amount) AS total_amount_topay'
            //'SUM(sales_payments_tocredit.payment_amount) AS total_amount_payed'
		);
		$this->db->from( $this->db->dbprefix('sales').' AS sales' );
		$this->db->join( $this->db->dbprefix('sales_payments')." AS sales_payments" , 'sales_payments.sale_id = sales.sale_id', 'inner');
        //$this->db->join( $this->db->dbprefix('sales_payments_tocredit')." AS sales_payments_tocredit" , 'sales_payments_tocredit.sale_id = sales.sale_id', 'inner');
		$this->db->where('sales_payments.payment_type', $this->lang->line('sales_due'));
		$this->db->where('sales.customer_id',$person_id);
		$this->db->where('sales.sale_status != 2');
		$result = $this->db->get()->result_array();
		
		$total_amount_topay = $result[0]['total_amount_topay'];
		
		$this->db->select(
            //'SUM(sales_payments.payment_amount) AS total_amount_topay'
			'SUM(sales_payments_tocredit.payment_amount) AS total_amount_payed'
		);
		$this->db->from( $this->db->dbprefix('sales').' AS sales' );
		$this->db->join( $this->db->dbprefix('sales_payments')." AS sales_payments" , 'sales_payments.sale_id = sales.sale_id', 'inner');
		$this->db->join( $this->db->dbprefix('sales_payments_tocredit')." AS sales_payments_tocredit" , 'sales_payments_tocredit.sale_id = sales.sale_id', 'inner');
		$this->db->where('sales_payments.payment_type', $this->lang->line('sales_due'));
		$this->db->where('sales.customer_id',$person_id);
		$this->db->where('sales.sale_status != 2');
		$result = $this->db->get()->result_array();
		
		$total_amount_payed = $result[0]['total_amount_payed'];

		$totalcredits = floatval($total_amount_topay) - floatval($total_amount_payed); 
		
		return $totalcredits;
	}
	
	public function get_all_salestocredit($person_id){
		
		$this->db->select('sales_payments.*');
		$this->db->from( $this->db->dbprefix('sales').' AS sales' );
		$this->db->join( $this->db->dbprefix('sales_payments')." AS sales_payments" , 'sales_payments.sale_id = sales.sale_id', 'inner');
		$this->db->where('sales.customer_id',$person_id);
		$this->db->where('sales_payments.payment_type', $this->lang->line('sales_due'));
		$this->db->where('sales.sale_status != 2');
		
		$this->db->order_by('sales.sale_time', 'asc');
		
		return  $this->db->get()->result_array();
	}

	public function get_all_salespaymentstocredit($sale_id){
		
		$this->db->select(
			'SUM(sales_payments_tocredit.payment_amount) AS total_amount_payed'
		);
		$this->db->from( $this->db->dbprefix('sales_payments_tocredit').' AS sales_payments_tocredit' );
		$this->db->where('sales_payments_tocredit.sale_id',$sale_id);
		
		$result = ($this->db->get()->result_array())[0]['total_amount_payed'];
		
		if(empty($result)){
			return 0.0;
		}else{
			return $result;
		}
	}

	public function pay_totalcredits($person_id){
		
		$sales_due = $this->get_all_salestocredit($person_id);

		$totalpayed = 0.0;

		foreach($sales_due as $row){
			$total_amount_payed = $this->get_all_salespaymentstocredit($row['sale_id']);
			
			
			$rest_amount = floatval($row['payment_amount']) - floatval($total_amount_payed);
			
			if($rest_amount > 0){
				$this->save_payment_tocredit($row['sale_id'], $rest_amount , $id = null);
				$totalpayed += $rest_amount;
			}
		}

		$result = [
			'data' => [
				'totalpayed'  => number_format($totalpayed,2),
				'rest_credit' => number_format(0.0, 2),
				'client_name' => ($this->get_person_info($person_id)["first_name"]) ." " . (($this->get_person_info($person_id))["last_name"])
			],
			'status' => true
		];

		return $result;
	}

	public function partialpay_credits($person_id, $amount_topay){
		
		$sales_due = $this->get_all_salestocredit($person_id);
		$totalpayed = $amount_topay; 

		foreach($sales_due as $row){
			$total_amount_payed = $this->get_all_salespaymentstocredit($row['sale_id']);
			$rest_amount = floatval($row['payment_amount']) - floatval($total_amount_payed);
			if($rest_amount > 0){
				if($amount_topay >= $rest_amount){
					$this->save_payment_tocredit($row['sale_id'], $rest_amount , $id = null);
					$amount_topay = $amount_topay - $rest_amount; 
				}else if($amount_topay > 0 && $amount_topay < $rest_amount){
					$this->save_payment_tocredit($row['sale_id'], $amount_topay , $id = null);
					$amount_topay = 0.0;
				}
			}
		}
		
		$result = [
			'data' => [
				'totalpayed'  => number_format($totalpayed, 2),
				'rest_credit' => number_format($this->get_sale_totalcredits($person_id), 2),
				'client_name' => ($this->get_person_info($person_id)["first_name"]) ." " . (($this->get_person_info($person_id))["last_name"])
			],
			'status' => true
		];

		return $result;       
	}

	public function get_person_info($person_id){
		$this->db->select();
		$this->db->from( $this->db->dbprefix('people') );
		$this->db->where('person_id',$person_id);
		return ($this->db->get()->result_array())[0];
	}

// 	cambio para ventas del dia y mes.. completas y suspendidas
	public function get_sales_thisday(){
		
		$this->db->select(
			'SUM( sales_items.item_unit_price * sales_items.quantity_purchased ) AS total_sales_this_day'
		);
		$this->db->from( $this->db->dbprefix('sales_payments').' AS sales_payments' );
		$this->db->join( $this->db->dbprefix('sales')." AS sales" , 'sales_payments.sale_id = sales.sale_id', 'inner');
		$this->db->join( $this->db->dbprefix('sales_items')." AS sales_items" , 'sales_items.sale_id = sales.sale_id', 'inner');

		$this->db->where('sales.sale_time >=', date("Y-m-d")." 00:00:00" );
		$this->db->where('sales.sale_time <=', date("Y-m-d")." 23:59:59" );

		$this->db->where('sales.employee_id', $this->session->userdata('person_id') );
		$this->db->where('sales.sale_status != 2');

        //var_dump($this->db->get_compiled_select());  
        //var_dump( $this->session->userdata('person_id') );


		$result = ($this->db->get()->result_array())[0]['total_sales_this_day'];
		
		if(empty($result)){
			return 0.0;
		}else{
			return $result;
		}
		
		return 0.0;
	}

	public function get_sales_thismonth(){
		
		$this->db->select(
			'SUM( sales_items.item_unit_price * sales_items.quantity_purchased ) AS total_sales_this_month'
		);
		$this->db->from( $this->db->dbprefix('sales_payments').' AS sales_payments' );
		$this->db->join( $this->db->dbprefix('sales')." AS sales" , 'sales_payments.sale_id = sales.sale_id', 'inner');
		$this->db->join( $this->db->dbprefix('sales_items')." AS sales_items" , 'sales_items.sale_id = sales.sale_id', 'inner');

		$this->db->where('sales.sale_time >=', date("Y-m-d", strtotime("-1 month"))." 00:00:00");
		$this->db->where('sales.sale_time <=', date("Y-m-d")." 23:59:59" );

		$this->db->where('sales.employee_id', $this->session->userdata('person_id') );
		$this->db->where('sales.sale_status != 2');

		$result = ($this->db->get()->result_array())[0]['total_sales_this_month'];
		
		if(empty($result)){
			return 0.0;
		}else{
			return $result;
		}
		
		return 0.0;
	}

	
	public function get_salessuspended_thisday(){
		
		$this->db->select(
			'SUM( sales_items.item_unit_price * sales_items.quantity_purchased ) AS total_salessuspended_this_day'
		);
		$this->db->from( $this->db->dbprefix('sales_payments').' AS sales_payments' );
		$this->db->join( $this->db->dbprefix('sales')." AS sales" , 'sales_payments.sale_id = sales.sale_id', 'inner');
		$this->db->join( $this->db->dbprefix('sales_items')." AS sales_items" , 'sales_items.sale_id = sales.sale_id', 'inner');

		$this->db->where('sales.sale_time >=', date("Y-m-d")." 00:00:00" );
		$this->db->where('sales.sale_time <=', date("Y-m-d")." 23:59:59" );

		$this->db->where('sales.employee_id', $this->session->userdata('person_id') );
		$this->db->where('sales.sale_status', 2 );

        //var_dump($this->db->get_compiled_select());  
        //var_dump( $this->session->userdata('person_id') );


		$result = ($this->db->get()->result_array())[0]['total_salessuspended_this_day'];
		
		if(empty($result)){
			return 0.0;
		}else{
			return $result;
		}
		
		return 0.0;
	}

	public function get_salessuspended_thismonth(){
		
		$this->db->select(
			'SUM( sales_items.item_unit_price * sales_items.quantity_purchased ) AS total_salessuspended_this_month'
		);
		$this->db->from( $this->db->dbprefix('sales_payments').' AS sales_payments' );
		$this->db->join( $this->db->dbprefix('sales')." AS sales" , 'sales_payments.sale_id = sales.sale_id', 'inner');
		$this->db->join( $this->db->dbprefix('sales_items')." AS sales_items" , 'sales_items.sale_id = sales.sale_id', 'inner');

		$this->db->where('sales.sale_time >=', date("Y-m-d", strtotime("-1 month"))." 00:00:00");
		$this->db->where('sales.sale_time <=', date("Y-m-d")." 23:59:59" );

		$this->db->where('sales.employee_id', $this->session->userdata('person_id') );
		$this->db->where('sales.sale_status', 2 );

		$result = ($this->db->get()->result_array())[0]['total_salessuspended_this_month'];
		
		if(empty($result)){
			return 0.0;
		}else{
			return $result;
		}
		
		return 0.0;
	}
// 	/cambio para ventas del dia y mes.. completas y suspendidas
	public function get_items_in_sale($sale_id){

        //$this->db->reset_query();

		$this->db->select(("items.* , sales_items.* " ));
		
		$this->db->from( $this->db->dbprefix('items')." AS items" );
		$this->db->join( $this->db->dbprefix('sales_items')." AS sales_items" , 'sales_items.item_id = items.item_id', 'left');
		$this->db->join( $this->db->dbprefix('sales')." AS sales" , 'sales.sale_id = sales_items.sale_id', 'left');
		
		$this->db->where('sales.sale_id', $sale_id );

        //$this->db->limit(10);


		return $this->db->get()->result_array();
		
	}
	
    // nuker 13-11-22 llamar total abonos para cuentas por cobrar
	public function get_abonos($sale_id)
	{   
		$this->db->select('CASE WHEN paytocredit.sale_id IS NULL THEN 0 ELSE SUM(payment_amount) END as payment_amount'); 
		$this->db->from('sales_payments_tocredit as paytocredit'); 
		$this->db->join('sales', 'paytocredit.sale_id = sales.sale_id', 'left');
		
		$this->db->where('paytocredit.sale_id', $sale_id );
		
 
		$query = $this->db->get();

		return $query->row();


	}

	public function get_deudas($sale_id)
	{   
		$this->db->select('SUM(payment_amount) as payment_amount'); 
		$this->db->from('sales_payments as  payments'); 
		$this->db->join('sales', 'payments.sale_id = sales.sale_id', 'left'); 

		$this->db->where('payment_type',$this->lang->line('sales_due'));
		$this->db->where('payments.sale_id', $sale_id );
		$query = $this->db->get();

		return $query->row();


	}

	public function get_data_sale($sale_id)
	{   
		$this->db->select('customer_id, employee_id, sale_status'); 
		$this->db->from('sales');   
		$this->db->where('sale_id', $sale_id );
		$query = $this->db->get();

		return $query->row();


	}
	
    // nuker 13-11-22 crons ejecutado al cargar cuentas por cobrar, desactiva muestra de deuda si esta cancelada la deuda
	public function update_estatus_credit($sale_id, $status)
	{ 
		$this->db->where('sale_id', $sale_id);
		$this->db->update('sales', $status); 
	} 

	public function update_estatus_credit_p($sale_id, $status)
	{ 
		$this->db->where('sale_id', $sale_id);
		$this->db->update('sales_payments_tocredit', $status); 
	} 

	public function update_estatus_abonado($sale_id, $status)
	{ 
		$this->db->where('sale_id', $sale_id);
		$this->db->update('sales', $status); 
	} 

	public function update_estatus_abonado_p($sale_id, $status)
	{ 
		$this->db->where('sale_id', $sale_id);
		$this->db->update('sales_payments_tocredit', $status); 
	} 
 

}
?>
