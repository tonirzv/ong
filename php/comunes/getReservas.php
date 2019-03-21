<?php session_start();
if ($_SESSION['permisos']==3) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Reserva.php';
require '../clases/Funciones.php';
require '../clases/ReservaProducto.php';
require '../clases/ReservaEvento.php';
require '../clases/Conexion.php';

$resultado=array(
	'reservasProductos'=> \reservaProductoClases\ReservaProducto::getReservas('','',$_SESSION['id']),
	'reservasEventos' => \reservaEventoClases\ReservaEvento::getReservas('','',$_SESSION['id'])
	);
echo json_encode($resultado);
} else echo false;
 ?>