<?php session_start();
if (isset($_SESSION['permisos']) && $_SESSION['permisos']==3) {
	require '../clases/update.php';
	require '../interfaces/abm.php';
	require '../clases/Funciones.php';
	require '../clases/Producto.php';
	require '../clases/Conexion.php';
	require '../clases/Reserva.php';
	require '../clases/ReservaProducto.php';
	$json = file_get_contents('php://input');
	$resp = json_decode($json);
	$obj = $resp->reservaProducto;

	$reservaProducto = new \reservaProductoClases\ReservaProducto(null,
											$_SESSION['id'],
											$obj->cantidad,
											null,
											null,
											$obj->producto,
											$obj->tienda->id
											);
	\reservaProductoClases\ReservaProducto::reservarProducto($reservaProducto,$obj->precio);
	echo json_encode(array('resp' => 'OK'));
} else echo json_encode(array('resp' => 'FAIL'));
?>