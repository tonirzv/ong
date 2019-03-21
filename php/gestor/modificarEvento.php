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
for ($i=0; $i < count($obj->fechasPosibles) ; $i++) { 
	$obj->fechasPosibles[$i] = new DateTime($obj->fechasPosibles[$i]);
	$obj->fechasPosibles[$i]=$obj->fechasPosibles[$i]->format('Y-m-d');
}

$evento = new \eventoClases\Evento(
								$obj->id,
								$obj->nombre,
								$obj->descripcion,
								$obj->ruta,
								$obj->participantes,
								$obj->entradasdisponibles,
								$obj->aforo,
								$obj->precioentrada,
								$obj->lugar,
								$fecha,
								$obj->fechasPosibles);
\eventoClases\Evento::modificar($evento);
} else echo false;
?>

