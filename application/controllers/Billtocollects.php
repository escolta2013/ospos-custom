<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

define('PRICE_MODE_STANDARD', 0);
define('PRICE_MODE_KIT', 1);
define('PAYMENT_TYPE_UNASSIGNED', '--');

class Billtocollects extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('billtocollects');

	} 

	public function index()
	{ 
		$this->session->set_userdata('allow_temp_items', 1);
		$this->input();
	} 

 

	public function manages($start_date, $end_date, $employee_id, $customer_id)
	{ 
		$data = array(
			'start_date' =>$start_date ,
			'end_date'=>$end_date,
			'employee_id'=>$employee_id,
			'customer_id'=>$customer_id,
			'sale_status' =>COMPLETED,
		); 
		
		$person_id = $this->session->userdata('person_id');

		if(!$this->Employee->has_grant('billtocollects', $person_id))
		{
			redirect('no_access/billtocollects/');
		}
		else
		{
			$data['table_headers'] = get_billtocollect_manage_table_headers();



			$data['filters'] = array(
				'start_date' => $start_date,
				'end_date' => $end_date,
				'employee_id' => $employee_id,
				'customer_id'=>$customer_id,
				'sale_status' =>COMPLETED,
			);

			$this->load->view('billtocollect/manage', $data);
		}
	}

	public function input()
	{   
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$employee_info = $this->Employee->get_info($employee_id);
		$perfil = $employee_info->perfil;

		$person_id = $this->session->userdata('person_id');

		if(!$this->Employee->has_grant('billtocollects', $person_id))
		{
			redirect('no_access/billtocollects/');
		}
		else
		{
			$data['table_headers'] = get_billtocollect_manage_table_headers();

			$this->load->model('Employee');
			$this->load->model('Customer');
			$employees = array();

			$i = 0;	
			/*	if ($perfil!=1) {*/
			//$employees[$employee_id] = $employee_info->first_name.' '.$employee_info->last_name;            
				/*}else{*/
					$employees['all'] = $this->lang->line('sales_no_filter');
					foreach ($this->Employee->get_all()->result() as $employee) {
						$employees[$employee->person_id] = $employee->first_name.' '.$employee->last_name;
						$i++;
					}
					/*	} */


					$customers = array();
					$j = 0; 
					$customers['all'] = $this->lang->line('sales_no_filter');
					foreach ($this->Customer->get_all()->result() as $customer) {
						$customers[$customer->person_id] = $customer->first_name.' '.$customer->last_name;
						$j++;
					}

					$data["employees"] = $employees;	/*/re*/
					$data["customers"] = $customers;	/*/re*/

					$this->load->view('billtocollect/inputs', $data);
				}
			}

			public function get_row($row_id)
			{
				$sale_info = $this->Billtocollect->get_info($row_id)->row();
				$data_row = $this->xss_clean(get_billtocollect_data_row($sale_info));

				echo json_encode($data_row);
			}

			public function search()
			{


				$search = $this->input->get('search');
				$limit = $this->input->get('limit');
				$offset = $this->input->get('offset');
				$sort = $this->input->get('sort');
				$order = $this->input->get('order');

				$filters = array('sale_type' => 'all',
					'location_id' => 'all',
					'start_date' => $this->input->get('start_date'),
					'end_date' => $this->input->get('end_date'),
					'employee_id' => $this->input->get('employee_id'),
					'customer_id' => $this->input->get('customer_id'),
					'sale_status' => COMPLETED,
					'only_cash' => FALSE,
					'only_due' => FALSE,
					'only_check' => FALSE,
					'only_invoices' => $this->config->item('invoice_enable') && $this->input->get('only_invoices'),
					'is_valid_receipt' => $this->Billtocollect->is_valid_receipt($search));

		// check if any filter is set in the multiselect dropdown
				$filledup = array_fill_keys($this->input->get('filters'), TRUE);
				$filters = array_merge($filters, $filledup);

				$sales = $this->Billtocollect->search($search, $filters, $limit, $offset, $sort, $order);
				$total_rows = $this->Billtocollect->get_found_rows($search, $filters);
				$payments = $this->Billtocollect->get_payments_summary($search, $filters);
				$payments_tc = $this->Billtocollect->get_payments_tc_summary($search, $filters);
				$payment_summary = $this->xss_clean(get_billtocollect_manage_payments_summary($payments, $payments_tc,  $sales));

				$data_rows = array();
				foreach($sales->result() as $sale)
				{
					$data_rows[] = $this->xss_clean(get_billtocollect_data_row($sale));
				}

				if($total_rows > 0)
				{
					$data_rows[] = $this->xss_clean(get_billtocollect_data_last_row($sales, $payments, $payments_tc));
				}

				echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'payment_summary' => $payment_summary));
			} 
  
}
?>
