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
$fecha = new DateTime($obj->fecha);
$fecha=$fecha->format('Y-m-d');
$evento = new \eventoClases\Evento($obj->idevento,
					null,
					null,
					null,
					null,
					$obj->entradasdisponibles,
					$obj->aforo,
					$obj->precioentrada,
					$obj->lugar,
					null,
					$fecha
					);

$evento->altaExistente();

} else echo false;
 ?>