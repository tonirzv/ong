<?php session_start();
if (isset($_SESSION['id'])) {
	
	echo json_encode(array('resp'=>'OK',
		'permisos'=>$_SESSION['permisos'],
		'id'=>$_SESSION['id'],
		'nombre'=>$_SESSION['nombre']));

} else echo json_encode(array('resp'=>'FAIL'));
 ?>