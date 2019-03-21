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
$obj = $resp->participante;


$participante = new \participanteClases\Participante(
					null,
					$obj->nif,
					$obj->nombre,
					$obj->apellido1,
					$obj->apellido2,
					$obj->email,
					$obj->telefono,
					$obj->direccion,
					$obj->localidad,
					$obj->provincia,
					$obj->grupo
					);

\participanteClases\Participante::altaParticipante($participante);
} else echo false;
 ?>