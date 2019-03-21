<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Grupo.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->grupo;

$grupo = new \grupoClases\Grupo(
					$obj->id,
					$obj->cif,
					$obj->nombre,
					$obj->domiciliofiscal,
					$obj->web
					);
\grupoClases\Grupo::modificarGrupo($grupo);
} else echo false;
?>