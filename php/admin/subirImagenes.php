<?php session_start();
if ($_SESSION['permisos']==1 || $_SESSION['permisos']==2) {
	require '../clases/Conexion.php';
	require '../interfaces/abm.php';
	require '../clases/Funciones.php';
	require '../clases/update.php';
	require '../clases/Producto.php';
	
	$carpeta = \productoClases\Producto::getCarpeta($_POST['folder']);

	for ($i=0; $i < count($_FILES) ; $i++) { 
	    	$tmp_name = $_FILES[$i]['tmp_name'];
			$filename = $_FILES[$i]['name'];
			if (move_uploaded_file($tmp_name, '../../img/productos/'.$carpeta.'/'.$filename)) {
			$rutas = array();
			array_push($rutas, 'productos/'.$carpeta.'/'.$filename);
			\productoClases\Producto::insertarImagenes($carpeta,$rutas);
		}
	}
} else echo false;
?>