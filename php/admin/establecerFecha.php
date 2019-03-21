<?php session_start();
if ($_SESSION['permisos']==1) { 
require '../interfaces/abm.php';
require '../clases/update.php';
require '../clases/Conexion.php';
require '../clases/Funciones.php';
require '../clases/Evento.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$obj = $resp->evento;
$fecha = new DateTime($resp->fechaEstablecida);
$fecha=$fecha->format('Y-m-d');
\eventoClases\Evento::establecerFecha($obj->id,$fecha);
} else echo false;
 ?>