<?php session_start();
if (isset($_SESSION['permisos'])) {
require '../interfaces/abm.php';
require '../clases/Persona.php';
require '../clases/update.php';
require '../clases/Usuario.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->usuario;

$usuario = new \usuarioClases\Usuario(
					$obj->id,
					$obj->nif,
					$obj->nombre,
					$obj->apellido1,
					$obj->apellido2,
					$obj->email,
					$obj->telefono,
					$obj->direccion,
					$obj->localidad,
					$obj->provincia,
					$obj->password,
					null,
					null
					);

\usuarioClases\Usuario::modificar($usuario);
} else echo false;
 ?>