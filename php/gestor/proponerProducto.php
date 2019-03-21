<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Producto.php';
require '../clases/Categoria.php';
require '../clases/Tienda.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->producto;
$fecha = new DateTime($obj->fecha);
$fecha = $fecha->format('Y-m-d');

if ($obj->stock=='0') {$estado = '3';} 
else {$estado = '1';}

$producto = new \productoClases\Producto(null,
										$obj->rutas,
										$obj->nombre,
										$obj->stock,
										$obj->precio,
										$obj->categoria,
										$fecha,
										$obj->tienda,
										$obj->descripcion,
										$estado
										);
$producto->alta();

} else echo false;
 ?>