<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Producto.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->producto;
$idRutasBorrar = '';
$rutasInsertar = '';
$fecha = new DateTime($obj->fecha);
$fecha=$fecha->format('Y-m-d');
$producto = new \productoClases\Producto(
					$obj->id,
					$obj->rutas,
					$obj->nombre,
					$obj->stock,
					$obj->precio,
					$obj->categoria,
					$fecha,
					$obj->tienda,
					$obj->descripcion,
					$obj->estado);
\productoClases\Producto::modificar($producto,$idRutasBorrar,$rutasInsertar);
} else echo false;
 ?>