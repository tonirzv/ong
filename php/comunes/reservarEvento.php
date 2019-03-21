<?php session_start();
if (isset($_SESSION['permisos']) && $_SESSION['permisos']==3) {
	require '../clases/update.php';
	require '../interfaces/abm.php';
	require '../clases/Funciones.php';
	require '../clases/Evento.php';
	require '../clases/Conexion.php';
	require '../clases/Reserva.php';
	require '../clases/ReservaEvento.php';
	$json = file_get_contents('php://input');
	$resp = json_decode($json);
	$obj = $resp->reservaEvento;

	$reservaEvento = new \reservaEventoClases\ReservaEvento( null,
															$_SESSION['id'],
															$obj->cantidad,
															null,
															null,
															$obj->evento
															);
	\reservaEventoClases\ReservaEvento::reservarEvento($reservaEvento,$obj->precio);
	echo json_encode(array('resp' => 'OK'));
} else echo json_encode(array('resp' => 'FAIL'));
?>