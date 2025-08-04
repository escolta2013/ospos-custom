<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Home extends Secure_Controller 
{
	public function __construct()
	{
		parent::__construct(NULL, NULL, 'home');
	}

	public function index()
	{   
    
    // Cambiar Tasa automaticamente con la tasa del BCV    
		if ($this->config->item('auto_cambio_tasa')==1) {

			$url1 = 'http://45.76.0.238:8500/monitor/bcv/';
			$url2 = 'http://45.76.0.238:8500/monitor/criptodolar/';

			if ($this->config->item('tasa_ref')==1 && $this->config->item('tasa')!=$tasa) { 
				$response = file_get_contents($url1);
			}else if ($this->config->item('tasa_ref')==2 && $this->config->item('tasa')!=$tasa) {
				$response = file_get_contents($url2);
			}
			        if ($response) {
			         	$data = json_decode($response, TRUE);  	    
						    
									 if ($this->config->item('tasa_ref')==1 && $this->config->item('tasa')!=$tasa) { 
									 	$formatted_value_bcv = number_format($data['usd']['price'], 2, '.', ',');
									 	$tasa=$formatted_value_bcv;
									 	$cambiotasa=1; 
									 }else if ($this->config->item('tasa_ref')==2 && $this->config->item('tasa')!=$tasa) {
									 	$formatted_value_paralelo =  number_format($data['enparalelovzla']['price'], 2, '.', ',');

									 	$tasa=$formatted_value_paralelo;
									 	$cambiotasa=1; 
									 }

									 if($this->config->item('tasa_ref')==1 && $this->config->item('tasa')==$tasa || $this->config->item('tasa_ref')==2 && $this->config->item('tasa')==$tasa){
									 	$cambiotasa=0; 
									 }

					 
						
									 if ($this->config->item('tasa')!=$tasa && $tasa>0) { 
									 	$batch_save_data = array(			
									 		'tasa' =>  empty($tasa) ? $this->config->item('tasa') : $tasa, 
									 	); 
									 	$this->Appconfig->batch_save($batch_save_data); 

									 } 
					   }  

          $data=array(
        	'bcv' =>$formatted_value_bcv , 
        	'enparalelovzla'=>$formatted_value_paralelo,
            'cambiotasa' => $cambiotasa); 
 

       }else{
       	$formatted_value=0;
        $cambiotasa=0; 
        $data=array(
        	'bcv' =>$this->config->item('tasa') , 
        	'enparalelovzla'=>$this->config->item('tasa'),
            'cambiotasa' => $cambiotasa); 

       }
		 

		$this->load->view('home/home',$data);
	}

	public function logout()
	{
		$this->Employee->logout();
	}

	/*
	Load "change employee password" form
	*/
	public function change_password($employee_id = -1)
	{
		$person_info = $this->Employee->get_info($employee_id);
		foreach(get_object_vars($person_info) as $property => $value)
		{
			$person_info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $person_info;

		$this->load->view('home/form_change_password', $data);
	}

	/*
	Change employee password
	*/
	public function save($employee_id = -1)
	{
		if($this->input->post('current_password') != '' && $employee_id != -1)
		{
			if($this->Employee->check_password($this->input->post('username'), $this->input->post('current_password')))
			{
				$employee_data = array(
					'username' => $this->input->post('username'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'hash_version' => 2
				);

				if($this->Employee->change_password($employee_data, $employee_id))
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('employees_successful_change_password'), 'id' => $employee_id));
				}
				else//failure
				{
					echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_unsuccessful_change_password'), 'id' => -1));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
			}
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
		}
	}
}
?>
