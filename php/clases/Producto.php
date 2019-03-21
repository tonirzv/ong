<?php  namespace productoClases;
class Producto implements \jsonSerializable, \abmInterfaces\abm {	
	use \updateClases\update;
	private $id;
	private $rutas;	
	private $nombre;
	private $stock;
	private $precio;
	private $categoria;
	private $fecha;
	private $tienda;
	private $descripcion;
	private $estado;

   function __construct($id,$rutas,$nombre,$stock,$precio,$categoria,$fecha,$tienda,$descripcion,$estado="1"){
		$this->id=$id;
		$this->rutas=$rutas;
		$this->nombre=$nombre;
		$this->stock=$stock;
		$this->precio=$precio;
		$this->categoria=$categoria;
		$this->fecha=$fecha;
		$this->tienda=$tienda;
		$this->descripcion=$descripcion;
		$this->estado=$estado;
   }
   
   function __set($var,$valor){
   	$this->$var=$valor;
   }

   function __get($var){
   	return $this->$var;
   }

   function jsonSerialize(){
   	return [
   		'id'=>$this->id,
		'rutas'=>$this->rutas,
		'nombre'=>$this->nombre,
		'stock'=>$this->stock,
		'precio'=>$this->precio,
		'categoria'=>$this->categoria,
		'fecha'=>$this->fecha,
		'tienda'=>$this->tienda,
		'descripcion'=>$this->descripcion,
		'estado'=>$this->estado
   	];
   }


   //inserto un producto totalmente nuevo
   function alta(){
		$datos = array();
		$params=array(':nombre' => $this->nombre,
					  ':descripcion' => $this->descripcion, 
					  ':categoria'=>$this->categoria, 
					  ':fecha'=>$this->fecha);

		$sql="INSERT INTO producto (id,nombre, descripcion, categoria, fecha_fin_campana) VALUES (null, :nombre,:descripcion, :categoria,:fecha);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		

		$params=array(':idtienda' => $this->tienda,
					  ':stock'=>$this->stock,
					  ':precio'=>$this->precio,
					  ':estado'=>$this->estado);

		$sql="INSERT INTO producto_tienda (id_producto_tienda, id_prod,id_tienda,stock,precio,estado) VALUES (null,LAST_INSERT_ID(),:idtienda, :stock,:precio,:estado);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		
		$statement=\funcionesClases\Funciones::doInsert($datos);

		//Creo la carpeta para las imágenes que añadiremos posteriormente
		$sql = "SELECT id from producto order by id desc limit 1";
		$statement=\funcionesClases\Funciones::doQuery($sql);
		$aux=$statement->fetchObject();
		$carpeta = $aux->id;
		mkdir($_SERVER['DOCUMENT_ROOT'] . '/ong/img/productos/'.$carpeta);
   }

   function baja($idProducto){
		$datos = array();
		$params = array(':idestado'=>4, ':idProducto'=>$idProducto);
				$sql = "UPDATE producto_tienda SET 
							estado =:idestado WHERE id_producto_tienda = :idProducto;";
				array_push($datos,array('query' =>$sql ,'params'=>$params));
				$statement=\funcionesClases\Funciones::doUpdate($datos);	
   }

   function aceptarProducto($idProducto){
		$datos = array();
		$params = array(':idestado'=>2, ':idProducto'=>$idProducto);
				$sql = "UPDATE producto_tienda SET 
							estado =:idestado WHERE id_producto_tienda = :idProducto;";
				array_push($datos,array('query' =>$sql ,'params'=>$params));
				$statement=\funcionesClases\Funciones::doUpdate($datos);	
   }

   //inserto un producto disponible en la tabla productos para otra tienda en la que no esté.
   function altaExistente(){
   	$datos=array();
   	$params=array(':idprod'=>$this->id, ':idtienda' => $this->tienda, ':stock'=>$this->stock,':precio'=>$this->precio,':estado'=>$this->estado);
		$sql="INSERT INTO producto_tienda (id_producto_tienda, id_prod,id_tienda,stock,precio,estado) VALUES (null,:idprod,:idtienda, :stock,:precio,:estado);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}

   //obtengo los productos filtrando por categoría y tienda
   function getProductos($categoria,$idTienda,$estado=''){
			$sql = "
			     SELECT 
			       id as idprod,
			       producto_tienda.id_producto_tienda as id,    
			       nombre,
			       producto_tienda.stock,
			       producto_tienda.precio,
			       categoria,
			       fecha_fin_campana as fecha,
			       producto_tienda.id_tienda,
			       descripcion,
			       producto_tienda.estado
			FROM producto inner join producto_tienda ON producto.id = producto_tienda.id_prod";

			if ($estado=='') {
				if ($categoria=="todas" && $idTienda!="todas") {
					$sql .= " where producto_tienda.id_tienda = :idtienda";
					$params=array(':idtienda'=> $idTienda);
					$statement=\funcionesClases\Funciones::doQuery($sql,$params);
				} elseif ($categoria!="todas" && $idTienda=="todas") {
					$sql .= " where producto.categoria = :categoria";
					$params=array(':categoria' => $categoria);
					$statement=\funcionesClases\Funciones::doQuery($sql,$params);
				} elseif ($categoria!="todas" && $idTienda!="todas") {
					$sql .= " where producto.categoria = :categoria and producto_tienda.id_tienda = :idtienda";
					$params=array(':categoria' => $categoria ,':idtienda'=> $idTienda);
					$statement=\funcionesClases\Funciones::doQuery($sql,$params);
				} else {
					$statement=\funcionesClases\Funciones::doQuery($sql);
				}
   			} elseif ($estado=='Aceptado') {
   				if ($categoria=="todas" && $idTienda!="todas") {
					$sql .= " where producto_tienda.id_tienda = :idtienda AND producto_tienda.estado = 2";
					$params=array(':idtienda'=> $idTienda);
					$statement=\funcionesClases\Funciones::doQuery($sql,$params);
				} elseif ($categoria!="todas" && $idTienda=="todas") {
					$sql .= " where producto.categoria = :categoria AND producto_tienda.estado = 2";
					$params=array(':categoria' => $categoria);
					$statement=\funcionesClases\Funciones::doQuery($sql,$params);
				} elseif ($categoria!="todas" && $idTienda!="todas") {
					$sql .= " where producto.categoria = :categoria and producto_tienda.id_tienda = :idtienda AND producto_tienda.estado= 2 ";
					$params=array(':categoria' => $categoria ,':idtienda'=> $idTienda);
					$statement=\funcionesClases\Funciones::doQuery($sql,$params);
				} else {
					$sql .= " where producto_tienda.estado = 2 ";
					$statement=\funcionesClases\Funciones::doQuery($sql);
				}
   			}
			
			$productos=array();
      		while ($aux=$statement->fetchObject()) {
      			$rutas = Producto::getImagenes($aux->idprod);
      			$estado = Producto::getEstado($aux->estado);
      			$tienda = array('id'=>$aux->id_tienda,'nombre'=>\tiendaClases\Tienda::getTienda($aux->id_tienda));
      			$producto = new Producto($aux->id,$rutas,$aux->nombre,$aux->stock,$aux->precio,$aux->categoria,$aux->fecha,$tienda,$aux->descripcion,$estado);
          		array_push($productos,$producto);
        	}
      return $productos;
	}

	//obtengo los productos de la tabla producto, para luego poder insertar uno ya existente en otra tienda
	//envío varios parámetros a null porque no están aún definidos
	function getProductosExistentes(){
       $sql = "SELECT * FROM producto";
			$statement=\funcionesClases\Funciones::doQuery($sql);
			$productos=array();
      		while ($aux=$statement->fetchObject()) {
      			$producto = new Producto($aux->id,null,$aux->nombre,null,null,$aux->categoria,$aux->fecha_fin_campana,null,$aux->descripcion,null);
          		array_push($productos,$producto);
        	}
      return $productos;
	}

	function getImagenes($id){
		$params=array(':id'=> $id);
		$sql ="SELECT id,ruta from imagenes_prod where imagenes_prod.id_prod= :id";
		$statement=\funcionesClases\Funciones::doQuery($sql,$params);

		$rutas['id']=array();
		$rutas['rutas']=array();
		while ($aux=$statement->fetchObject()) {
			array_push($rutas['id'], $aux->id);
			array_push($rutas['rutas'],$aux->ruta);
		}
		return $rutas;
	}

	//obtengo el estado de un producto para insertar dentro del constructor un string en lugar del id del estado
	function getEstado($id){
   	  $params=array(':id' => $id);
      $sql = "SELECT nombre from estado where id = :id";
      $statement=\funcionesClases\Funciones::doQuery($sql,$params);
      
      $aux=$statement->fetch(\PDO::FETCH_NUM);   
      return $aux[0];
   }

   function getEstados(){
      $sql = "SELECT * from estado WHERE  id ='2' OR id ='4'";
      $statement=\funcionesClases\Funciones::doQuery($sql);
      $estados=array();
  		while ($estado=$statement->fetchObject()) {
      		array_push($estados,$estado);
    	}
      return $estados;
   }


    function getCarpeta($idProd=''){
    if ($idProd=='') {
	  $sql="SELECT id from producto order by id desc limit 1;";
	  $statement=\funcionesClases\Funciones::doQuery($sql);
	  $aux=$statement->fetchObject();
	  return $aux->id;
    } else {
   	  		$params=array(':idprod'=>$idProd);
	  		$sql="SELECT id_prod as id from producto_tienda where id_producto_tienda = :idprod limit 1;";
	  		$statement=\funcionesClases\Funciones::doQuery($sql,$params);
	  		$aux=$statement->fetchObject();
	  		return $aux->id;
    	}
    }

   	function eliminarImagenes($idProd,$rutas){
		for ($i=0; $i < count($rutas); $i++) { 
			$sql ="DELETE FROM imagenes_prod 
						WHERE ruta = :ruta AND id_prod=(SELECT id_prod from producto_tienda where id_producto_tienda = :idprod limit 1);";
			$params=array(':idprod'=>$idProd,':ruta'=>$rutas[$i]);
			$statement=\funcionesClases\Funciones::doDelete($sql,$params);	
		}
	}

	function insertarImagenes($idProd,$rutas){
		$datos=array();
		for ($i=0; $i < count($rutas); $i++) { 
			$sql ="INSERT INTO imagenes_prod (id_prod,ruta) 
						VALUES (:idprod,:ruta);";	
			$params=array(':idprod'=>$idProd,':ruta'=>$rutas[$i]);
			array_push($datos, array('query'=>$sql,'params'=>$params));
		}
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}
}
 ?>

