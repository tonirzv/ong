<?php session_start();
if ($_SESSION['permisos']==1 || $_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Reserva.php';
require '../clases/Funciones.php';
require '../clases/ReservaProducto.php';
require '../clases/ReservaEvento.php';
require '../clases/Conexion.php';


$json = file_get_contents('php://input');
$obj = json_decode($json);

$resultado=array(
	'reservasProductos'=> \reservaProductoClases\ReservaProducto::getReservas($obj->fecha1,$obj->fecha2),
	'reservasEventos' => \reservaEventoClases\ReservaEvento::getReservas($obj->fecha1,$obj->fecha2)
	);

echo json_encode($resultado);
} else echo false;
 ?>