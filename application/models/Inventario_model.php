<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario_model extends CI_Model {

    public function start_inventory() {
        // Logica para iniciar un nuevo inventario
        $data = ['inventory_date' => date('Y-m-d H:i:s')];
        $this->db->insert('inventory_records', $data);
        return TRUE;
    }

    public function scan_item($item_number, $stock_location)
{
    // Registrar para depuración
    log_message('debug', "scan_item: item_number = $item_number, stock_location = $stock_location");

    // Buscar el artículo en ospos_items por el código de barras
    $this->db->from('items');
    $this->db->where('item_number', $item_number);
    $query = $this->db->get();

    if ($query->num_rows() > 0)
    {
        $item = $query->row_array();
        log_message('debug', "Artículo encontrado: " . print_r($item, true));

        // Obtener la cantidad en stock para la tienda seleccionada
        $this->db->select('quantity');
        $this->db->from('item_quantities');
        $this->db->where('item_id', $item['item_id']);
        $this->db->where('location_id', $stock_location);
        $q2 = $this->db->get();

        if ($q2->num_rows() > 0)
        {
            $item['expected_quantity'] = number_format($q2->row()->quantity, 0);

            log_message('debug', "Cantidad en stock obtenida: " . $item['expected_quantity']);
        }
        else
        {
            $item['expected_quantity'] = 0;
            log_message('debug', "No se encontró cantidad para este artículo en la ubicación $stock_location. Se asigna 0.");
        }

        return array('success' => true, 'item' => $item);
    }
    else
    {
        log_message('debug', "Artículo no encontrado para el código: $item_number");
        return array('success' => false, 'message' => 'Artículo no encontrado.');
    }
    }



    public function save_inventory_records($inventory, $inventory_date)
    {
    $this->db->trans_start();

    foreach ($inventory as $barcode => $item) {
        $data = [
            'item_id'           => $item['item_id'],
            'name'              => $item['name'],
            'category'          => $item['category'],
            'unit_price'        => $item['unit_price'],
            'expected_quantity' => $item['expected_quantity'],
            'counted_quantity'  => $item['counted_quantity'],
            'inventory_date'    => $inventory_date
        ];
        $this->db->insert('inventory_records', $data);
    }

    $this->db->trans_complete();
    return $this->db->trans_status();
    }



    public function get_inventory_report()
{
    // Simplemente devolvemos todas las filas o filtramos por fecha/ubicación
    return $this->db
        ->order_by('inventory_date DESC')
        ->get('inventory_records')
        ->result_array();
}

}
?>
