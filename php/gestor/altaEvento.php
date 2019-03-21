<?php session_start();
if ($_SESSION['permisos']==2) { 
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Evento.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->evento;
$fecha = new DateTime($obj->fechapropuesta);
$fecha = $fecha->format('Y-m-d');

$evento = new \eventoClases\Evento(null,
									$obj->nombre,
									$obj->descripcion,
									$obj->ruta,
									null,
									$obj->entradasdisponibles,
									$obj->aforo,
									$obj->precioentrada,
									$obj->lugar,
									null,
									$fecha
									);

$evento->alta();
} else echo false;
 ?>