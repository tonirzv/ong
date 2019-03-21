<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Persona.php';
require '../clases/Grupo.php';
require '../clases/Participante.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$obj = json_decode($json);
$datos = array(
	'participantes'=> \participanteClases\Participante::getParticipantes(),
	'grupos' => \grupoClases\Grupo::getGrupos()
);
echo json_encode($datos);
} else echo false;
?>