<?php session_start();
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Tienda.php';
require '../clases/Conexion.php';

$resultado=array('tiendas' =>\tiendaClases\Tienda::getTiendas());
echo json_encode($resultado);
 ?>