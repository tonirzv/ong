<?php session_start();
if (isset($_SESSION['id'])) {
require '../interfaces/abm.php';
require '../clases/update.php';
require '../clases/Persona.php';
require '../clases/Usuario.php';
require '../clases/Funciones.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$password = $resp->password;
$nuevaPassword = $resp->nuevapassword;
\usuarioClases\Usuario::modificarPassword($_SESSION['id'],$password,$nuevaPassword);
} else echo false;
 ?>