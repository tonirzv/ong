<?php namespace lugarClases;
class Lugar implements \jsonSerializable{
	private $id;
	private $lugar;

	function __construct($id,$lugar){
		$this->id=$id;
		$this->lugar=$lugar;
	}

	function jsonSerialize(){
   	return [
   		'id'=>$this->id,
		'lugar'=>$this->lugar
   	];
   }


  function __set($var,$valor){
    $this->$var=$valor;
   }

   function __get($var){
    return $this->$var;
   }

   function getLugares(){
   	  $sql = "SELECT id, lugar from lugar";
			$statement=\funcionesClases\Funciones::doQuery($sql);
			$lugares=array();
      		while ($aux=$statement->fetchObject()) {
      			$lugar = new Lugar($aux->id,$aux->lugar);
          		array_push($lugares,$lugar);
        	}
      return $lugares;
   }

   function getLugar($id){
    $params=array(':id' => $id);
      $sql = "SELECT lugar from lugar where id = :id";
      $statement=\funcionesClases\Funciones::doQuery($sql,$params);
      
      $aux=$statement->fetch(\PDO::FETCH_NUM);   
      return $aux[0];
   }
}
 ?>