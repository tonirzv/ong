<?php namespace categoriaClases;
class Categoria implements \jsonSerializable{
	private $id;
	private $nombre;

	function __construct($id,$nombre){
		$this->id=$id;
		$this->nombre=$nombre;
	}

	function jsonSerialize(){
   	return [
   	'id'=>$this->id,
		'nombre'=>$this->nombre
   	];
   }

   function getCategorias(){
   	  $sql = "SELECT * from categoria";
			$statement=\funcionesClases\Funciones::doQuery($sql);
			$categorias=array();
      		while ($aux=$statement->fetchObject()) {
      			$categoria = new Categoria($aux->id,$aux->nombre);
          		array_push($categorias,$categoria);
        	}
      return $categorias;
   }

   function getCategoria($id){
    $params=array(':id' => $id);
      $sql = "SELECT id, nombre from categoria where id = :id";
      $statement=\funcionesClases\Funciones::doQuery($sql,$params);
      $aux=$statement->fetch(\PDO::FETCH_NUM);   
      return $aux[0];
   }
}
?>