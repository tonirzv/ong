<?php namespace conexionClases;
class Conexion extends \PDO {
	function conectar(){
		try {
			$datos=parse_ini_file('../admin/bdconf.ini',true);
			if (isset($_SESSION['permisos'])) {
				$nombreBD = $datos['bd']['basededatos'];
				$usuario = $datos[$_SESSION['permisos']]['user'];
				$pass = $datos[$_SESSION['permisos']]['password'];
			} else {
				$nombreBD = $datos['bd']['basededatos'];
				$usuario = $datos[4]['user'];
				$pass = $datos[4]['password'];
			}

			$conexion = new \PDO('mysql:host=localhost;dbname='.$nombreBD.';charset=utf8', $usuario, $pass);
			$conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return $conexion;
		}
		catch(PDOException $e) {
			echo 'Error conectando con la base de datos: ' . $e->getMessage();
		}
	}
}
?>