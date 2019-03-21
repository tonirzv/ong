<?php session_start();
require '../clases/Persona.php';
require '../interfaces/abm.php';
require '../clases/update.php';
require '../clases/Usuario.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

if (isset($_SESSION['id'])) {
	$usuario = \usuarioClases\Usuario::getUsuario($_SESSION['id']);
	echo json_encode(array('resp'=>'OK','usuario' => $usuario));
} else echo json_encode(array('resp'=>'FAIL'));
 ?>