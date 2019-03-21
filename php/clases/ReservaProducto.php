<?php  namespace reservaProductoClases;
class ReservaProducto extends \reservaClases\Reserva implements \jsonSerializable {
	private $producto;
	private $tienda;
	function __construct($id,$usuario,$cantidad,$precioTotal,$fecha,$producto,$tienda){
		parent::__construct($id,$usuario,$cantidad,$precioTotal,$fecha);
		$this->producto=$producto;
		$this->tienda=$tienda;
	}

    function jsonSerialize(){
   	return [
   		'id'=>$this->id,
		'usuario'=>$this->usuario,
		'producto'=>$this->producto,
		'tienda'=>$this->tienda,
		'cantidad'=>$this->cantidad,
		'precioTotal'=>$this->precioTotal,
    	'fecha'=>$this->fecha
   		];
    }

    function getReservas($fecha1='',$fecha2='',$id=''){
      if ($id=='') {
		       $sql = "SELECT * from reserva_prod";
		      if (!empty($fecha1) && !empty($fecha2)) {
		      	$sql .= " where fecha >= :fecha1 and  fecha <= :fecha2";
		      	$params=array(':fecha1' => $fecha1,':fecha2'=>$fecha2);
		      	$statement=\funcionesClases\Funciones::doQuery($sql,$params);
		      } elseif (empty($fecha1) && !empty($fecha2)) {
		      	$sql .= " where fecha <= :fecha2";
		      	$params=array(':fecha2'=>$fecha2);
		      	$statement=\funcionesClases\Funciones::doQuery($sql,$params);
		      } elseif (!empty($fecha1) && empty($fecha2)) {
		      	$sql .= " where fecha >= :fecha1";
		      	$params=array(':fecha1'=>$fecha1);
		      	$statement=\funcionesClases\Funciones::doQuery($sql,$params);
		      } else {
		      	$statement=\funcionesClases\Funciones::doQuery($sql);
		      }

     } else {
        $sql = "SELECT * from reserva_prod WHERE id_usuario = :id ORDER BY fecha DESC";
        $params=array(':id' => $id);
        $statement=\funcionesClases\Funciones::doQuery($sql,$params);
      }

		  $reservasProductos=array();
  		while ($aux=$statement->fetchObject()) {

  			$sqlUsuario = "SELECT concat(nombre,' ',apellido1,' Tlfn: ', telefono,' e-mail: ', email) as datos from usuario where id =:idusuario;";
  			$paramsUsuario=array(':idusuario' => $aux->id_usuario);
  			$statementUsuario=\funcionesClases\Funciones::doQuery($sqlUsuario,$paramsUsuario);
  			$usuario = $statementUsuario->fetchObject();

  			$sqlTienda = "SELECT nombre from tienda where id =:idtienda;";
  			$paramsTienda=array(':idtienda' => $aux->id_tienda);
  			$statementTienda=\funcionesClases\Funciones::doQuery($sqlTienda,$paramsTienda);
  			$tienda = $statementTienda->fetchObject();

  			$sqlProducto = "SELECT nombre from producto where id =(SELECT id_prod from producto_tienda where id_producto_tienda=:idproducto limit 1);";
  			$paramsProducto=array(':idproducto' => $aux->id_producto);
  			$statementProducto=\funcionesClases\Funciones::doQuery($sqlProducto,$paramsProducto);
  			$producto = $statementProducto->fetchObject();

  			$reserva = new ReservaProducto($aux->id_reserva,
                                       $usuario->datos,
                                       $aux->cantidad,
                                       $aux->precio_total,
                                       $aux->fecha,
                                       $producto,
                                       $tienda->nombre);
      		array_push($reservasProductos,$reserva);
    	}
      return $reservasProductos;
    }

    function reservarProducto(ReservaProducto $reservaProducto,$precioProducto){
    	$datos = array();
    	$precioTotal = ($reservaProducto->cantidad * $precioProducto);

    	$params = array(':idusuario'=> $reservaProducto->usuario,
    					':idproducto'=>$reservaProducto->producto,
    					':idtienda'=>$reservaProducto->tienda,
    					':cantidad'=>$reservaProducto->cantidad,
    					':preciototal'=>$precioTotal
    					);

    	$sql="INSERT INTO reserva_prod (id_reserva, id_usuario, id_producto, id_tienda, cantidad, precio_total,fecha) VALUES (NULL, :idusuario, (SELECT id_prod from producto_tienda where id_producto_tienda=:idproducto), :idtienda, :cantidad, :preciototal,CURRENT_DATE);";

    	array_push($datos, array('query'=>$sql,'params'=>$params));
		
		$params = array(
    					':idproducto'=>$reservaProducto->producto,
    					':cantidad'=>$reservaProducto->cantidad
    					);
    	$sql="UPDATE producto_tienda SET stock =(stock - :cantidad) WHERE id_producto_tienda=:idproducto";
    	array_push($datos, array('query'=>$sql,'params'=>$params));

      $params = array(
              ':idproducto'=>$reservaProducto->producto
              );
    	$sql="UPDATE producto_tienda SET estado = 3 WHERE stock=0 AND id_producto_tienda=:idproducto";
    	array_push($datos, array('query'=>$sql,'params'=>$params));

		$statement=\funcionesClases\Funciones::doInsert($datos);
    }
}
 ?>