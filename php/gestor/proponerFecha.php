<?php session_start();
if ($_SESSION['permisos']==2) {
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Evento.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$resp = json_decode($json);
$fecha = new DateTime($resp->fecha);
$fecha=$fecha->format('Y-m-d');
\eventoClases\Evento::proponerFecha($resp->idEvento,$fecha);
} else echo false;
 ?>