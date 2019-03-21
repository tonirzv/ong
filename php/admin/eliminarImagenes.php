<?php session_start();
if ($_SESSION['permisos']==1 || $_SESSION['permisos']==2) {
	require '../clases/Conexion.php';
	require '../interfaces/abm.php';
	require '../clases/update.php';
	require '../clases/Producto.php';
	require '../clases/Funciones.php';

	$json = file_get_contents('php://input');
	$resp = json_decode($json);
	$idprod = $resp->idproducto;
	$rutas = $resp->rutas;

	\productoClases\Producto::eliminarImagenes($idprod,$rutas);;
} else echo false;
 ?>