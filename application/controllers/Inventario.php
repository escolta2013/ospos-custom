<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once("Secure_Controller.php");
class Inventario extends Secure_Controller {

    public function __construct() {
        // 'inventario' debe coincidir con module_id en la tabla ospos_modules
        parent::__construct('inventario');

        $this->load->model('Inventario_model');
        $this->load->model('Stock_location');
        // Ya no necesitas chequeos manuales de permisos: Secure_Controller lo hizo por ti
    }

    public function index()
{
    // Cargamos datos de empleado
    $employee_info = $this->Employee->get_logged_in_employee_info();
    $is_admin      = (bool)$employee_info->is_admin;

    // OJO: aqui debes usar 'inventario', no 'inventory'
    $locations = $this->Stock_location->get_allowed_locations('inventario');

    // Definimos la tienda por defecto
    if ($is_admin) {
        // Si eres admin, por ejemplo, tomamos la primera ubicacion
        $stock_location = key($locations);
    } else {
        // Si no, forzamos su ubicacion asignada
            $stock_location = $employee_info->inventory_location_id ?: key($locations);

    }

    // Cargamos categorias
    $this->db->distinct()->select('category');
    $cats = $this->db->order_by('category')->get('items')->result_array();
    $categories = ['' => 'Todas'];
    foreach ($cats as $c) {
        $categories[$c['category']] = $c['category'];
    }

    // Enviamos todo a la vista
    $data = [
        'is_admin'        => $is_admin,
        'stock_locations' => $locations,
        'stock_location'  => $stock_location,
        'categories'      => $categories
    ];

    $this->load->view('inventario/manage', $data);
}



    public function start_inventory() {
        $ok = $this->Inventario_model->start_inventory();
    echo json_encode(['success' => $ok]);
    }

    public function scan_item() {
        $item_number    = $this->input->post('item_number');
        $stock_location = $this->input->post('stock_location');
        echo json_encode($this->Inventario_model->scan_item($item_number, $stock_location));
    }

    public function finish_inventory()
{
    $inventory      = $this->input->post('inventory');       // tu array de items
    $stock_location = $this->input->post('stock_location');
    $now            = date('Y-m-d H:i:s');

    // Guarda todas las filas en la misma tabla, incluyendo item_id
    $success = $this->Inventario_model->save_inventory_records($inventory, $now);

    echo json_encode([
        'success' => $success,
        'message' => $success 
            ? 'Inventario guardado correctamente.' 
            : 'Ocurrio un error al guardar el inventario.'
    ]);
}


    public function get_inventory_report() {
        echo json_encode([
            'success' => true,
            'data'    => $this->Inventario_model->get_inventory_report()
        ]);
    }
}
