<?php namespace grupoClases;
class Grupo implements \jsonSerializable {
	private $id;
	private $cif;
	private $nombre;
	private $domicilioFiscal;
	private $web;

	function __construct($id,$cif,$nombre,$domicilioFiscal,$web){
		$this->id=$id;
		$this->cif=$cif;
		$this->nombre=$nombre;
		$this->domicilioFiscal=$domicilioFiscal;
		$this->web=$web;
	}


	function jsonSerialize(){
		return [
			'id'=>$this->id,
			'cif'=>$this->cif,
			'nombre'=>$this->nombre,
			'domiciliofiscal'=>$this->domicilioFiscal,
			'web'=>$this->web,	
		];
	}

	function getGrupos(){
		$sql ="SELECT id, CIF, nombre, domicilio_fiscal, web from grupo";
		$statement=\funcionesClases\Funciones::doQuery($sql);
		$grupos=array();
		while ($aux=$statement->fetchObject()){
			$grupo = new Grupo($aux->id,$aux->CIF,$aux->nombre,$aux->domicilio_fiscal,$aux->web);
			array_push($grupos,$grupo);
		}
		return $grupos;
	}

	function getGrupo($idGrupo=''){
		$sql ="SELECT id, CIF, nombre, domicilio_fiscal, web from grupo";
		if ($idGrupo=='') {
			return 'Sin grupo';
		} else {
			$params=array(':id' => $idGrupo);
      		$sql = "SELECT nombre from grupo where id = :id";
      		$statement=\funcionesClases\Funciones::doQuery($sql,$params);
      		$aux=$statement->fetch(\PDO::FETCH_NUM);   
      		return $aux[0];
		}
	}

	function altaGrupo(Grupo $grupo){
		$datos=array();
   		$params=array(':cif'=>$grupo->cif, ':nombre'=>$grupo->nombre, ':domiciliofiscal'=>$grupo->domicilioFiscal,':web'=>$grupo->web);
		$sql ="INSERT INTO grupo (id,CIF, nombre, domicilio_fiscal, web) VALUES (null,:cif,:nombre,:domiciliofiscal,:web)";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}

	function modificarGrupo(Grupo $grupo){
		$datos = array();
		$params = array(
				':id'=>$grupo->id,
				':cif'=>$grupo->cif,
				':nombre'=>$grupo->nombre, 
				':domiciliofiscal'=>$grupo->domicilioFiscal,
				':web'=>$grupo->web
				);

		$sql = "UPDATE grupo SET 
					CIF =:cif,
					nombre =:nombre,
					domicilio_fiscal =:domiciliofiscal,
					web =:web
					WHERE id = :id;";
		array_push($datos,array('query' =>$sql ,'params'=>$params));
		$statement=\funcionesClases\Funciones::doUpdate($datos);				
	}

}
 ?>

