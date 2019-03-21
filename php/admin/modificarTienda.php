<?php session_start();
if ($_SESSION['permisos']==1) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Tienda.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->tienda;
var_dump($obj);
$tienda = new \tiendaClases\Tienda(
					$obj->id,
					$obj->nombre,
					$obj->direccion,
					$obj->cp,
					$obj->ciudad,
					$obj->telefono,
					$obj->correo,
					$obj->fax,
					$obj->latitud,
					$obj->longitud);
\tiendaClases\Tienda::modificar($tienda);

} else echo false;
 ?>