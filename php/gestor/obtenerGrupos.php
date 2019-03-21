<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/Grupo.php';
require '../interfaces/abm.php';
require '../clases/update.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$obj = json_decode($json);
$datos = array(
	'grupos' => \grupoClases\Grupo::getGrupos()
);

echo json_encode($datos);
} else echo false;
?>