<?php 
 function altaExistente(){
   	$datos=array();
   	$params=array(':idprod'=>$this->id, ':idtienda' => $this->tienda, ':stock'=>$this->stock,':precio'=>$this->precio,':estado'=>$this->estado);
		$sql="INSERT INTO producto_tienda (id_producto_tienda, id_prod,id_tienda,stock,precio,estado) VALUES (null,:idprod,:idtienda, :stock,:precio,:estado);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);
   }
 ?>