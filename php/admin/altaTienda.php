<?php session_start();
if ($_SESSION['permisos']==1) {
require '../clases/update.php';
require '../clases/Funciones.php';
require '../clases/Tienda.php';
require '../clases/Conexion.php';
require '../interfaces/abm.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->tienda;


$tienda = new \tiendaClases\Tienda(null,
					$obj->nombre,
					$obj->direccion,
					$obj->cp,
					$obj->ciudad,
					$obj->telefono,
					$obj->correo,
					$obj->fax,
					$obj->latitud,
					$obj->longitud
					);

\tiendaClases\Tienda::altaTienda($tienda);
} else echo false;
?>