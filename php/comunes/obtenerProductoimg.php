<?php 
require '../clases/update.php';
require '../interfaces/abm.php';
require '../clases/Funciones.php';
require '../clases/Producto.php';
require '../clases/Categoria.php';
require '../clases/Tienda.php';
require '../clases/Conexion.php';

$json = file_get_contents('php://input');
$obj = json_decode($json);

$resultado=array(
	'tiendas' =>\tiendaClases\Tienda::getTiendas(),
	'categorias'=> \categoriaClases\Categoria::getCategorias(),
	'productos' => \productoClases\Producto::getProductos($obj->categoria,$obj->tienda)
	);
echo json_encode($resultado);
 ?>