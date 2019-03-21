<?php 
require '../clases/Funciones.php';
require '../clases/Conexion.php';
$json = file_get_contents('php://input');
$obj = json_decode($json);
$email = $obj->email;
$password = $obj->password;

$datosUsuario=\funcionesClases\Funciones::login($email,$password);

if (!$datosUsuario==false) {
	echo json_encode(array('resultado' => true,'datosUsuario'=>$datosUsuario));		
} else {
	echo json_encode(array('resultado' => false));
}
 ?>

