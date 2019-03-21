<?php 
require '../interfaces/abm.php';
require '../clases/Persona.php';
require '../clases/update.php';
require '../clases/Usuario.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->usuario;

$password = password_hash($obj->password1,PASSWORD_BCRYPT);

$usuario = new \usuarioClases\Usuario(
					null,
					$obj->nif,
					$obj->nombre,
					$obj->apellido1,
					$obj->apellido2,
					$obj->telefono,
					$obj->email,
					$obj->direccion,
					$obj->localidad,
					$obj->provincia,
					$password,
					null,
					null
					);
\usuarioClases\Usuario::alta($usuario);
echo json_encode(array('resp' => 'OK'));
 ?>