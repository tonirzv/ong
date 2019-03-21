<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Persona.php';
require '../clases/Participante.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);

\participanteClases\Participante::eliminarParticipantes($resp->idEvento,$resp->aEliminar);
\participanteClases\Participante::agregarParticipantes($resp->idEvento,$resp->aAgregar);
} else echo false;
 ?>