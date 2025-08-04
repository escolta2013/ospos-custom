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
        // Cambiar Tasa automáticamente con la tasa del BCV    
        if ($this->config->item('auto_cambio_tasa') == 1) {
            $url = 'https://pydolarve.org/api/v1/dollar?page=bcv';
            $response = file_get_contents($url);

            if ($response) {
                $data = json_decode($response, TRUE);
                $tasa = number_format($data['monitors']['usd']['price'], 2, '.', ',');
                $cambiotasa = 0;

                // Verificar si se usa tasa_ref para decidir si hay que actualizar la tasa
                if ($this->config->item('tasa_ref') == 1 && $this->config->item('tasa') != $tasa) { 
                    $batch_save_data = array(
                        'tasa' => $tasa,
                    ); 
                    $this->Appconfig->batch_save($batch_save_data); 
                    $cambiotasa = 1; 
                } elseif ($this->config->item('tasa_ref') == 2 && $this->config->item('tasa') != $tasa) {
                    // Aquí puedes manejar otra lógica si tienes otra fuente para tasa_ref 2
                    // Por ahora, simplemente lo dejamos así
                }
            } else {
                // Si no se pudo obtener la tasa, usar la tasa configurada
                $tasa = $this->config->item('tasa');
                $cambiotasa = 0; 
            }
        } else {
            $tasa = $this->config->item('tasa');
            $cambiotasa = 0; 
        }

        $data = array(
            'bcv' => $tasa, 
            'cambiotasa' => $cambiotasa
        ); 

        $this->load->view('home/home', $data);
    }

    public function logout()
    {
        $this->Employee->logout();
    }

    public function change_password($employee_id = -1)
    {
        $person_info = $this->Employee->get_info($employee_id);
        foreach (get_object_vars($person_info) as $property => $value) {
            $person_info->$property = $this->xss_clean($value);
        }
        $data['person_info'] = $person_info;

        $this->load->view('home/form_change_password', $data);
    }

    public function save($employee_id = -1)
    {
        if ($this->input->post('current_password') != '' && $employee_id != -1) {
            if ($this->Employee->check_password($this->input->post('username'), $this->input->post('current_password'))) {
                $employee_data = array(
                    'username' => $this->input->post('username'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'hash_version' => 2
                );

                if ($this->Employee->change_password($employee_data, $employee_id)) {
                    echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('employees_successful_change_password'), 'id' => $employee_id));
                } else {
                    echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_unsuccessful_change_password'), 'id' => -1));
                }
            } else {
                echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
            }
        } else {
            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
        }
    }
}
?>
