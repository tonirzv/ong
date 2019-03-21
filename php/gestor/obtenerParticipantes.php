<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Persona.php';
require '../clases/Funciones.php';
require '../clases/Grupo.php';
require '../clases/Participante.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$obj = json_decode($json);

$participantes = \participanteClases\Participante::getParticipantesWhereNotIn($obj->idEvento);

echo json_encode($participantes);
} else echo false;
?>
