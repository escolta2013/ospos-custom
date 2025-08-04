<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Expenses extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('expenses');
	}

public function index()
{
    // 1. Obtén el JSON original
    	$data['table_headers'] = $this->xss_clean(get_expenses_manage_table_headers());
    	
    

    // 2. Decodifícalo a array
    $headers = json_decode($headers_json, true);
    if (!is_array($headers)) {
        $headers = array();
    }

    
   // 1) Trae los headers existentes (vienen como JSON)
$headers = json_decode(get_expenses_manage_table_headers(), true) ?: [];



// 4) Reconvierte a JSON sanitizado
$data['table_headers'] = $this->xss_clean(json_encode($headers));


    // filtros que ya tenías
    $data['filters'] = array(
        'only_cash'   => $this->lang->line('expenses_cash_filter'),
        'only_due'    => $this->lang->line('expenses_due_filter'),
        'only_check'  => $this->lang->line('expenses_check_filter'),
        'only_credit' => $this->lang->line('expenses_credit_filter'),
        'only_debit'  => $this->lang->line('expenses_debit_filter'),
        'is_deleted'  => $this->lang->line('expenses_is_deleted')
    );

    // 5. Carga la vista
    $this->load->view('expenses/manage', $data);
}


public function search()
{
    $search = $this->input->get('search');
    $limit = $this->input->get('limit');
    $offset = $this->input->get('offset');
    $sort = $this->input->get('sort');
    $order = $this->input->get('order');

    $filters = array(
        'start_date' => $this->input->get('start_date'),
        'end_date' => $this->input->get('end_date'),
        'only_cash' => FALSE,
        'only_due' => FALSE,
        'only_check' => FALSE,
        'only_credit' => FALSE,
        'only_debit' => FALSE,
        'is_deleted' => FALSE
    );

    // Cargar el helper si no está autoloaded
    $this->load->helper('expenses');

    // Check if any filter is set in the multiselect dropdown
    $filledup = array_fill_keys($this->input->get('filters'), TRUE);
    $filters = array_merge($filters, $filledup);

    $expenses = $this->Expense->search($search, $filters, $limit, $offset, $sort, $order);
    $total_rows = $this->Expense->get_found_rows($search, $filters);
    $payments = $this->Expense->get_payments_summary($search, $filters);
    $accounts_payable = $this->Expense->get_accounts_payable_summary($search, $filters);
    $total_expenses = $this->Expense->get_total_expenses($search, $filters); // Nuevo

    $data_rows = array();
    foreach ($expenses->result() as $expense) {
    // 1) Obtén los datos tal como antes
    $row = get_expenses_data_row($expense);
    // 2) Sobrescribe sólo la fecha, quitando la hora:
    $row['date'] = date('d/m/Y', strtotime($expense->date));
    // 3) Limpia y agrega al array final
    $data_rows[] = $this->xss_clean($row);
    }

    if ($total_rows > 0) {
        $data_rows[] = $this->xss_clean(get_expenses_data_last_row($expenses));
    }

    echo json_encode(array(
        'total' => $total_rows,
        'rows' => $data_rows,
        'payment_summary' => get_expenses_manage_payments_summary($payments, $expenses),
        'accounts_payable_summary' => get_accounts_payable_summary($accounts_payable),
        'total_expenses' => to_currency($total_expenses) // Nuevo
    ));
}

	public function view($expense_id = -1)
	{
		
		
		$data = array();

		$data['employees'] = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			foreach(get_object_vars($employee) as $property => $value)
			{
				$employee->$property = $this->xss_clean($value);
			}

			$data['employees'][$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
		}

		$data['expenses_info'] = $this->Expense->get_info($expense_id);

		$expense_categories = array();
		foreach($this->Expense_category->get_all(0, 0, TRUE)->result_array() as $row)
		{
			$expense_categories[$row['expense_category_id']] = $row['category_name'];
		}
		$data['expense_categories'] = $expense_categories;

		$expense_id = $data['expenses_info']->expense_id;

		if(empty($expense_id))
		{
			$data['expenses_info']->date = date('Y-m-d H:i:s');
			$data['expenses_info']->employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		}

		$data['payments'] = array();
		foreach($this->Expense->get_expense_payment($expense_id)->result() as $payment)
		{
			foreach(get_object_vars($payment) as $property => $value)
			{
				$payment->$property = $this->xss_clean($value);
			}

			$data['payments'][] = $payment;
		}

		// don't allow gift card to be a payment option in a sale transaction edit because it's a complex change
		$data['payment_options'] = $this->xss_clean($this->Expense->get_expense_payment_options());


		$this->load->view("expenses/form", $data);
	}

	public function get_row($row_id)
	{
		$expense_info = $this->Expense->get_info($row_id);
		$data_row = $this->xss_clean(get_expenses_data_row($expense_info));

		echo json_encode($data_row);
	}

public function save($expense_id = -1)
{
    // 1) Fechas
    $newdate = $this->input->post('date');
    $date_formatter = date_create_from_format(
        $this->config->item('dateformat'),
        $newdate
    );
    if (!$date_formatter) {
        $date_formatter = new DateTime();
    }

    $due_date_raw = $this->input->post('due_date');
    $due_date_obj = DateTime::createFromFormat('d/m/Y', $due_date_raw);
    $due_date = $due_date_obj
        ? $due_date_obj->format('Y-m-d')
        : null;

    // 2) Estado de pago: tomamos siempre lo que venga del formulario, validando
    $raw_status = $this->input->post('payment_status');
    $allowed = ['pending','partial','paid'];
    $payment_status = in_array($raw_status, $allowed)
        ? $raw_status
                        :'pending';

    // 3) Monto adeudado: si pagado → 0, si no → lo que venga o NULL
    if ($payment_status === 'paid') {
        $amount_due = 0;
    } else {
        $raw_due = $this->input->post('amount_due');
        $amount_due = ($raw_due !== '' && $raw_due !== null)
            ? parse_decimals($raw_due)
            : null;
    }

    // 4) Montamos el array
    $expense_data = array(
        'date'                => $date_formatter->format('Y-m-d'),
        'supplier_id'         => $this->input->post('supplier_id') === '' 
                                   ? null 
                                   : $this->input->post('supplier_id'),
        'supplier_tax_code'   => $this->input->post('supplier_tax_code'),
        'amount'              => parse_decimals($this->input->post('amount')),
        'tax_amount'          => parse_decimals($this->input->post('tax_amount')),
        'payment_type'        => $this->input->post('payment_type'),
        'expense_category_id' => $this->input->post('expense_category_id'),
        'description'         => $this->input->post('description'),
        'employee_id'         => $this->input->post('employee_id'),
        'deleted'             => $this->input->post('deleted') != null,
        'due_date'            => $due_date,
        'payment_status'      => $payment_status,
        'amount_due'          => $amount_due
    );

    // 5) Guardamos y devolvemos JSON
    if ($this->Expense->save($expense_data, $expense_id))
    {
        $expense_data = $this->xss_clean($expense_data);

        echo json_encode(array(
            'success' => TRUE,
            'message' => $expense_id == -1
                ? $this->lang->line('expenses_successful_adding')
                : $this->lang->line('expenses_successful_updating'),
            'id'      => $expense_id == -1
                ? $expense_data['expense_id']
                : $expense_id
        ));
    }
    else
    {
        echo json_encode(array(
            'success' => FALSE,
            'message' => $this->lang->line('expenses_error_adding_updating'),
            'id'      => -1
        ));
    }
}



	public function ajax_check_amount()
	{
		$value = $this->input->post();
		$parsed_value = parse_decimals(array_pop($value));
		echo json_encode(array('success' => $parsed_value !== FALSE));
	}

	public function delete()
	{
		$expenses_to_delete = $this->input->post('ids');

		if($this->Expense->delete_list($expenses_to_delete))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('expenses_successful_deleted') . ' ' . count($expenses_to_delete) . ' ' . $this->lang->line('expenses_one_or_multiple'), 'ids' => $expenses_to_delete));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('expenses_cannot_be_deleted'), 'ids' => $expenses_to_delete));
		}
	}
}
?>
