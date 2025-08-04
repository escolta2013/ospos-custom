<?php
ob_start(); // Inicia el buffer para evitar errores de encabezados
require_once 'index.php'; // Carga el n¨²cleo de CodeIgniter/OSPOS
$CI =& get_instance(); // Obtiene la instancia de CodeIgniter
$CI->load->library('migration'); // Carga la biblioteca de migraciones

if ($CI->migration->latest() === FALSE) {
    echo "Error al ejecutar la migracion: " . $CI->migration->error_string();
} else {
    echo "Migracion ejecutada con exito.";
}
ob_end_flush(); // Libera el buffer
?>