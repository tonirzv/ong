<?php namespace tiendaClases;
class Tienda implements \jsonSerializable {
	use \updateClases\update;
	private $id;
	private $nombre;
	private $direccion;
	private $cp;
	private $ciudad;
	private $telefono;
	private $correo;
	private $fax;
	private $latitud;
	private $longitud;

	function __construct($id,$nombre, $direccion, $cp, $ciudad, $telefono, $correo, $fax,$latitud,$longitud){
		$this->id=$id;
 		$this->nombre = $nombre;
 		$this->direccion = $direccion;
 		$this->cp = $cp;
 		$this->ciudad = $ciudad;
 		$this->telefono = $telefono;
 		$this->correo = $correo;
 		$this->fax = $fax;
 		$this->latitud = $latitud;
 		$this->longitud = $longitud;
	}

	function jsonSerialize(){
   	return [
   		'id'=>$this->id,
		'nombre'=>$this->nombre,
		'direccion'=>$this->direccion,
		'cp'=>$this->cp,
		'ciudad'=>$this->ciudad,
		'telefono'=>$this->telefono,
		'correo'=>$this->correo,
		'fax'=>$this->fax,
		'latitud'=>$this->latitud,
		'longitud'=>$this->longitud
   	];
   }

   function getTiendas(){
   	$sql = "SELECT * from tienda";
			$statement=\funcionesClases\Funciones::doQuery($sql);
			$tiendas=array();
      		while ($aux=$statement->fetchObject()) {
      			$tienda = new Tienda($aux->id,$aux->nombre,$aux->direccion,$aux->codigo_postal,$aux->ciudad,$aux->telefono,$aux->email,$aux->fax,$aux->latitud,$aux->longitud);
          		array_push($tiendas,$tienda);
        	}
      return $tiendas;
   }

   function getTienda($id){
    $params=array(':id' => $id);
      $sql = "SELECT nombre from tienda where id = :id";
      $statement=\funcionesClases\Funciones::doQuery($sql,$params);
      
      $aux=$statement->fetch(\PDO::FETCH_NUM);   
      return $aux[0];
   }

   function altaTienda(Tienda $tienda){
   		$datos=array();
   		$params=array(':nombre'=>$tienda->nombre, ':direccion'=>$tienda->direccion, ':ciudad'=>$tienda->ciudad,':codigopostal'=>$tienda->cp, ':telefono'=>$tienda->telefono, ':email'=>$tienda->correo, ':fax'=>$tienda->fax, ':latitud'=>$tienda->latitud,':longitud'=>$tienda->longitud);

		$sql ="INSERT INTO tienda (id, nombre, direccion, ciudad, codigo_postal, telefono, email, fax,latitud,longitud) VALUES (NULL,:nombre, :direccion, :ciudad, :codigopostal, :telefono, :email, :fax, :latitud, :longitud)";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);
   }
}
 ?>