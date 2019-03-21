<?php session_start();
if ($_SESSION['permisos']==1) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Persona.php';
require '../clases/Grupo.php';
require '../clases/Participante.php';
require '../clases/Lugar.php';
require '../clases/Evento.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$obj = json_decode($json);

$resultado=array(
	'lugares'=> lugarClases\Lugar::getLugares(),
	'eventos' => eventoClases\Evento::getEventos($obj->fecha1,$obj->fecha2),
	'eventosexistentes' => eventoClases\Evento::getEventosExistentes()
	);

echo json_encode($resultado);
} else echo false;
 ?>