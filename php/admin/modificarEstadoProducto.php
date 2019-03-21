<?php session_start();
if ($_SESSION['permisos']==1) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Producto.php';
require '../clases/Conexion.php';


$json = file_get_contents('php://input');
$resp = json_decode($json);
var_dump($resp);
$estado = $resp->estado;
$producto = $resp->producto;

if ($estado=='4') {
	\productoClases\Producto::baja($producto);
} else if ($estado=='2') {
	\productoClases\Producto::aceptarProducto($producto);
}

} else echo false;
?>